<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Carbon\Carbon;

class TrackVisitors
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah dihitung hari ini (pakai Session)
        if (!session()->has('has_visited')) {

            // Tambah/Update data pengunjung hari ini
            $today = Carbon::today()->toDateString();

            $visitor = Visitor::firstOrCreate(
                ['visit_date' => $today],
                ['count' => 0]
            );

            $visitor->increment('count');

            // Tandai di session biar kalau refresh tidak dihitung lagi
            session()->put('has_visited', true);
        }

        return $next($request);
    }
}