<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class ProgressController extends Controller
{
    public function index(Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);

        return redirect()->route('customer.bookings.show', $booking);
    }
}
