<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tambal_ban_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string'
        ]);

        $review = \App\Models\Review::create([
            'user_id' => auth()->id(),
            'tambal_ban_id' => $request->tambal_ban_id,
            'rating' => $request->rating,
            'komentar' => $request->komentar
        ]);

        // Load data user agar nama user bisa ditampilkan
        $review->load('user');

        // Kembalikan JSON untuk JavaScript
        return response()->json([
            'status' => 'success',
            'message' => 'Ulasan berhasil ditambahkan!',
            'data' => $review
        ]);
    }
}
