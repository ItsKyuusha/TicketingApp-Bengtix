<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentType;

class PaymentTypeController extends Controller
{
    public function index()
    {
        $types = PaymentType::all();
        return view('pages.admin.payment-types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:payment_types,nama',
        ]);

        PaymentType::create($validated);
        return back()->with('success', 'Tipe pembayaran berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $type = PaymentType::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:payment_types,nama,' . $type->id,
        ]);

        $type->update($validated);
        return back()->with('success', 'Tipe pembayaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        $type = PaymentType::findOrFail($id);

        // Belum dipakai di order, aman dihapus
        $type->delete();

        return back()->with('success', 'Tipe pembayaran berhasil dihapus');
    }
}
