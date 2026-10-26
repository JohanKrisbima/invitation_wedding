<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Couple;
use App\Models\Invitation;
use App\Models\Order;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of orders with server-side filtering, search, and pagination.
     */
    public function index(Request $request)
    {
        // 1. Metric Counts for Order Management Overview
        $totalOrders = Order::count();
        $unpaidOrders = Order::where('payment_status', 'unpaid')->count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $inProgressOrders = Order::where('order_status', 'in_progress')->count();
        $completedOrders = Order::where('order_status', 'completed')->count();

        // 2. Query Builder for Orders Table (Server-Side)
        $query = Order::with(['template', 'invitation'])->latest();

        // Live Search Filter (order number, customer name, WhatsApp phone)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'ILIKE', "%{$search}%")
                  ->orWhere('order_number', 'ILIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'ILIKE', "%{$search}%");
            });
        }

        // Status Filter
        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('order_status', $status);
            }
        }

        // Payment Status Filter
        if ($payment = $request->input('payment')) {
            if ($payment !== 'all') {
                $query->where('payment_status', $payment);
            }
        }

        // Paginate Server-Side (10 per page)
        $orders = $query->paginate(10)->withQueryString();

        // If AJAX request (live search, filter change, or pagination click), return only table partial
        if ($request->ajax() || $request->header('X-Alpine-Request') || $request->boolean('ajax')) {
            return view('admin.orders._table', compact('orders'))->render();
        }

        return view('admin.orders.index', compact(
            'orders',
            'totalOrders',
            'unpaidOrders',
            'pendingOrders',
            'inProgressOrders',
            'completedOrders'
        ));
    }

    /**
     * Show form to manually create a new order (admin manual entry).
     */
    public function create()
    {
        $templates = Template::where('is_active', true)->get();

        return view('admin.orders.create', compact('templates'));
    }

    /**
     * Store a newly created order into the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'template_id' => ['required', 'exists:templates,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'wedding_date' => ['required', 'date'],
            'customer_address' => ['nullable', 'string', 'max:500'],
            'total_price' => ['required', 'numeric', 'min:0'],
            'payment_status' => ['required', 'in:unpaid,paid'],
            'order_status' => ['required', 'in:pending,in_progress,completed,cancelled'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'auto_create_invitation' => ['nullable', 'boolean'],
        ], [
            'template_id.required' => 'Pilih salah satu template katalog.',
            'customer_name.required' => 'Nama pemesan / calon pengantin wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp pemesan wajib diisi.',
            'wedding_date.required' => 'Tanggal rencana pernikahan wajib diisi.',
            'total_price.required' => 'Total harga pesanan wajib diisi.',
            'payment_status.required' => 'Status pembayaran wajib ditentukan.',
            'order_status.required' => 'Status order wajib ditentukan.',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            // Generate unique Order Number e.g. ORD-839201
            do {
                $orderNumber = 'ORD-' . mt_rand(100000, 999999);
            } while (Order::where('order_number', $orderNumber)->exists());

            $order = Order::create([
                'order_number' => $orderNumber,
                'template_id' => $validated['template_id'],
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'wedding_date' => $validated['wedding_date'],
                'customer_address' => $validated['customer_address'] ?? null,
                'total_price' => $validated['total_price'],
                'payment_status' => $validated['payment_status'],
                'order_status' => $validated['order_status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // If admin opted to immediately generate the invitation
            if ($request->boolean('auto_create_invitation')) {
                // Ensure order is marked paid and in progress
                $order->update([
                    'payment_status' => 'paid',
                    'order_status' => 'in_progress',
                ]);

                // Generate clean slug
                $baseSlug = Str::slug($order->customer_name);
                $slug = $baseSlug ?: 'undangan-' . strtolower($order->order_number);
                $count = 1;
                while (Invitation::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-{$count}";
                    $count++;
                }

                $invitation = Invitation::create([
                    'order_id' => $order->id,
                    'template_id' => $order->template_id,
                    'slug' => $slug,
                    'publication_status' => 'draft',
                ]);

                Couple::create([
                    'invitation_id' => $invitation->id,
                    'partner_one_name' => $order->customer_name,
                    'partner_two_name' => 'Pasangan',
                    'partner_one_father' => '-',
                    'partner_one_mother' => '-',
                    'partner_two_father' => '-',
                    'partner_two_mother' => '-',
                ]);

                return redirect()->route('admin.invitations.edit', $invitation->id)
                    ->with('success', "Pesanan {$order->order_number} berhasil dibuat dan lunas! Undangan digital telah dibuatkan, silakan lengkapi profil mempelai.");
            }

            return redirect()->route('admin.orders.index')
                ->with('success', "Pesanan baru {$order->order_number} atas nama {$order->customer_name} berhasil dibuat!");
        });
    }
}
