<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    public function create() { return redirect()->route('admin.users.index'); }
    public function store() { return redirect()->route('admin.users.index'); }
    public function show() { return redirect()->route('admin.users.index'); }
    public function edit() { return redirect()->route('admin.users.index'); }
    public function update() { return redirect()->route('admin.users.index'); }
}