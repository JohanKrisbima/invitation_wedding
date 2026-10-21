<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the main admin dashboard with stats and server-side orders table.
     */
    public function index(Request $request)
    {
        // 1. Metric Stats
        $totalOrders = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $inProgressOrders = Order::where('order_status', 'in_progress')->count();
        $completedOrders = Order::where('order_status', 'completed')->count();

        // 2. Query Builder for Orders Table (Server-Side)
        $query = Order::with(['template', 'invitation'])->latest();

        // Search Filter
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

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'inProgressOrders',
            'completedOrders',
            'orders'
        ));
    }
}
