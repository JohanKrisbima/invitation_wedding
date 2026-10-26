<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Order;
use App\Models\Template;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the main admin monitoring dashboard.
     */
    public function index()
    {
        // 1. Financial & Volume Metrics
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_price');
        $totalOrders = Order::count();
        $unpaidOrders = Order::where('payment_status', 'unpaid')->count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $inProgressOrders = Order::where('order_status', 'in_progress')->count();

        // 2. Invitation Publication Metrics
        $totalInvitations = Invitation::count();
        $activeInvitations = Invitation::where('publication_status', 'published')->count();
        $draftInvitations = Invitation::where('publication_status', 'draft')->count();

        // 3. Catalog Metrics
        $totalTemplates = Template::where('is_active', true)->count();
        $popularTemplates = Template::withCount('orders')
            ->orderByDesc('orders_count')
            ->take(4)
            ->get();

        // 4. Recent Orders Feed (Monitoring latest 6 transactions)
        $recentOrders = Order::with(['template', 'invitation'])
            ->latest()
            ->take(6)
            ->get();

        // 5. Recent Invitations Feed (Monitoring latest 5 invitations)
        $recentInvitations = Invitation::with(['couple', 'template', 'order'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'unpaidOrders',
            'pendingOrders',
            'inProgressOrders',
            'totalInvitations',
            'activeInvitations',
            'draftInvitations',
            'totalTemplates',
            'popularTemplates',
            'recentOrders',
            'recentInvitations'
        ));
    }
}
