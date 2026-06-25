<?php
namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Portfolio;

class HomeController extends Controller
{
    public function index()
    {
        $packages   = Package::where('is_active', true)->take(3)->get();
        $portfolios = Portfolio::where('is_featured', true)->take(6)->get();
        return view('home.index', compact('packages', 'portfolios'));
    }

    public function packages()
    {
        $packages = Package::where('is_active', true)->get();
        return view('home.packages', compact('packages'));
    }

    public function portfolio()
    {
        $portfolios = Portfolio::latest()->paginate(12);
        $categories = Portfolio::select('category')->distinct()->pluck('category');
        return view('home.portfolio', compact('portfolios', 'categories'));
    }

    public function contact()
    {
        return view('home.contact');
    }
}