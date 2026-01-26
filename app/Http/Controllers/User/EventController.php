<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PaymentType;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Event $event)
    {
        $paymentTypes = PaymentType::all();
        $event->load(['tikets', 'kategori', 'user']);

        return view('events.show', compact('event', 'paymentTypes'));
    }
}
