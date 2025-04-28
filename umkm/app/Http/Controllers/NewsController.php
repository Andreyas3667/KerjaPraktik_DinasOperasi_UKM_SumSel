<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::all(); // Mengambil semua data berita
        return view('news.index', compact('news')); // Mengirim data ke view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('news.create'); // Menampilkan form untuk membuat berita baru
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|url',
            'published_at' => 'nullable|date',
        ]);

        News::create($validated); // Menyimpan data ke database
        return redirect()->route('news.index')->with('success', 'News created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        return view('news.show', compact('news')); // Menampilkan detail berita
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        return view('news.edit', compact('news')); // Menampilkan form untuk mengedit berita
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|url',
            'published_at' => 'nullable|date',
        ]);

        $news->update($validated); // Memperbarui data di database
        return redirect()->route('news.index')->with('success', 'News updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        $news->delete(); // Menghapus data dari database
        return redirect()->route('news.index')->with('success', 'News deleted successfully.');
    }
}
