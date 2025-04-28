<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\News; // Import model News
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the news.
     */
    public function index()
    {
        $news = News::all(); // Mengambil semua data berita dari database
        return view('front.news', compact('news')); // Mengirim data ke view
    }

    /**
     * Display the details of a specific news item.
     */
    public function details($id)
    {
        $news = News::findOrFail($id); // Mengambil berita berdasarkan ID atau gagal jika tidak ditemukan
        return view('front.news-detail', compact('news')); // Mengirim data ke view
    }
}
