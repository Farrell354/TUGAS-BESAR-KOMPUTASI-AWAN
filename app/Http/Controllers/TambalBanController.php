<?php

namespace App\Http\Controllers;

use App\Models\TambalBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // PENTING: Import Storage
use App\Models\User;

class TambalBanController extends Controller
{
    public function index()
    {
        $data = TambalBan::all();
        return view('admin.dashboard', compact('data'));
    }

public function create()
    {
        // Ambil semua user yang role-nya 'owner'
        $owners = User::where('role', 'owner')->get();
        return view('admin.create', compact('owners'));
    }

    // SIMPAN DATA BARU (+GAMBAR)
    public function store(Request $request)
    {
        $request->validate([
            'nama_bengkel'  => 'required|string|max:255',
            'nomer_telepon' => 'required|numeric',
            'alamat'        => 'nullable|string',
            'latitude'      => 'required',
            'longitude'     => 'required',
            'jam_buka'      => 'required',
            'jam_tutup'     => 'required',
            'kategori'      => 'required',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi Gambar
            'user_id' => 'nullable|exists:users,id',
        ]);

        $data = $request->all();

        // LOGIKA UPLOAD GAMBAR
        if ($request->hasFile('gambar')) {
            // Simpan ke folder 'public/bengkel_images'
            $path = $request->file('gambar')->store('bengkel_images', 'public');
            $data['gambar'] = $path;
        }


        TambalBan::create($data);

        return redirect()->route('dashboard')->with('success', 'Lokasi berhasil ditambahkan');
    }

public function edit($id)
    {
        $tambalBan = TambalBan::findOrFail($id);
        // Ambil semua owner untuk dropdown
        $owners = User::where('role', 'owner')->get();

        return view('admin.edit', compact('tambalBan', 'owners'));
    }

    // UPDATE DATA (+GANTI GAMBAR)
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bengkel'  => 'required|string|max:255',
            'nomer_telepon' => 'required|numeric',
            'alamat'        => 'nullable|string',
            'latitude'      => 'required',
            'longitude'     => 'required',
            'jam_buka'      => 'required',
            'jam_tutup'     => 'required',
            'kategori'      => 'required',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $tambalBan = TambalBan::findOrFail($id);
        $data = $request->all();

        // LOGIKA GANTI GAMBAR
        if ($request->hasFile('gambar')) {
            // 1. Hapus gambar lama jika ada
            if ($tambalBan->gambar && Storage::disk('public')->exists($tambalBan->gambar)) {
                Storage::disk('public')->delete($tambalBan->gambar);
            }

            // 2. Upload gambar baru
            $path = $request->file('gambar')->store('bengkel_images', 'public');
            $data['gambar'] = $path;
        }

        $tambalBan->update($data);

        return redirect()->route('dashboard')->with('success', 'Data berhasil diperbarui');
    }

    // HAPUS DATA (+HAPUS FILE GAMBAR)
    public function destroy($id)
    {
        $tambalBan = TambalBan::findOrFail($id);

        // Hapus file gambar dari penyimpanan
        if ($tambalBan->gambar && Storage::disk('public')->exists($tambalBan->gambar)) {
            Storage::disk('public')->delete($tambalBan->gambar);
        }

        $tambalBan->delete();

        return redirect()->route('dashboard')->with('success', 'Lokasi berhasil dihapus');
    }

    public function liveMap()
    {
        $lokasi = TambalBan::all();
        return view('admin.live-map', compact('lokasi'));
    }

    public function userDashboard()
    {
        $lokasi = TambalBan::all();
        return view('dashboard', compact('lokasi'));
    }
}
