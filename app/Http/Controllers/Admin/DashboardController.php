<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Tiket;
use App\Models\TicketType;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $totalEvents = Event::count();
        $totalCategories = \App\Models\Kategori::count();
        $totalTypeTicket = TicketType::count();
        $totalOrders = Order::count();
        return view('admin.dashboard', compact('totalEvents', 'totalCategories', 'totalTypeTicket', 'totalOrders'));
    }
}