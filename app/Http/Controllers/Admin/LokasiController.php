<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasis = Lokasi::all();
        return view('pages.admin.lokasi.index', compact('lokasis'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama_lokasi' => 'required|string|max:255']);
        Lokasi::create($request->only('nama_lokasi'));
        return back()->with('success', 'Lokasi ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $request->validate(['nama_lokasi' => 'required|string|max:255']);
        $lokasi->update($request->only('nama_lokasi'));
        return back()->with('success', 'Lokasi diperbarui');
    }

    public function destroy($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->delete();
        return back()->with('success', 'Lokasi dihapus');
    }
}

