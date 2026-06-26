<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('event_date')->get();
        return view('admin.events.index', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'event_date' => 'required|date',
            'location'   => 'nullable|string|max:255',
            'type'       => 'required|string',
            'color'      => 'nullable|string|max:7',
        ]);

        Event::create($validated);
        return back()->with('success', 'Event berhasil ditambahkan.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Event berhasil dihapus.');
    }

    // Stub methods agar resource route tidak error
    public function create()  { return redirect()->route('admin.events.index'); }
    public function show($id) { return redirect()->route('admin.events.index'); }
    public function edit($id) { return redirect()->route('admin.events.index'); }
    public function update(Request $request, Event $event) { return redirect()->route('admin.events.index'); }
}