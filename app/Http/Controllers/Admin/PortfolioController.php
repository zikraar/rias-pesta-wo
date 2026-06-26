<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::latest()->paginate(12);
        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|string',
            'image'    => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('image');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['image'] = $request->file('image')->store('portfolios', 'public');

        Portfolio::create($data);
        return back()->with('success', 'Foto portfolio berhasil ditambahkan.');
    }

    public function destroy(Portfolio $portfolio)
    {
        Storage::disk('public')->delete($portfolio->image);
        $portfolio->delete();
        return back()->with('success', 'Foto portfolio berhasil dihapus.');
    }

    public function create() { return redirect()->route('admin.portfolios.index'); }
    public function show($id) { return redirect()->route('admin.portfolios.index'); }
    public function edit($id) { return redirect()->route('admin.portfolios.index'); }
    public function update(Request $request, Portfolio $portfolio) { return redirect()->route('admin.portfolios.index'); }
}