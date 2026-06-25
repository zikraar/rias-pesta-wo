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
        $request->validate([
            'booking_id'  => 'required|exists:bookings,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_date' => 'nullable|date',
            'order'       => 'required|integer|min:1',
        ]);

        Progress::create($request->all());
        return back()->with('success', 'Progress berhasil ditambahkan.');
    }

    public function update(Request $request, Progress $progress)
    {
        $request->validate([
            'status'         => 'required|in:pending,on_progress,done',
            'description'    => 'nullable|string',
            'completed_date' => 'nullable|date',
            'attachment'     => 'nullable|image|max:2048',
        ]);

        $data = $request->only('status', 'description', 'completed_date');

        if ($request->status === 'done' && !$progress->completed_date) {
            $data['completed_date'] = now()->toDateString();
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