<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Progress, Booking};
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::whereIn('status', ['confirmed', 'in_progress'])
            ->with(['user', 'progress'])
            ->get();
        return view('admin.progress.index', compact('bookings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id'  => 'required|exists:bookings,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_date' => 'nullable|date',
            'order'       => 'required|integer|min:1',
        ]);

        Progress::create($validated);
        return back()->with('success', 'Progress berhasil ditambahkan.');
    }

    public function update(Request $request, Progress $progress)
    {
        if (in_array($progress->booking->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Booking sudah final, progress tidak bisa diubah lagi.');
        }

        $request->validate([
            'status'         => 'required|in:pending,on_progress,done',
            'description'    => 'nullable|string',
            'completed_date' => 'nullable|date',
            'attachment'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('status', 'description');

        if ($request->status === 'done') {
            $data['completed_date'] = $progress->completed_date ?: now()->toDateString();
        } else {
            $data['completed_date'] = null;
        }

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')
                ->store('progress', 'public');
        }

        $progress->update($data);

        // Notifikasi customer
        $progress->booking->user->notify(
            new \App\Notifications\ProgressUpdated($progress)
        );

        return back()->with('success', 'Progress diperbarui.');
    }

    public function destroy(Progress $progress)
    {
        $progress->delete();
        return back()->with('success', 'Progress dihapus.');
    }
}