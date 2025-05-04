<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class DashboardController extends Controller
{
    public function index()
    {
        $news = News::latest()->take(6)->get(); // Mengambil 6 berita terbaru
        return view('dashboard', compact('news'));
    }
}
