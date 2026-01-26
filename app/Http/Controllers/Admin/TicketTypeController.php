<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TicketType;

class TicketTypeController extends Controller
{
    public function index()
    {
        $types = TicketType::all();
        return view('pages.admin.ticket-types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:ticket_types,nama',
        ]);

        TicketType::create($validated);
        return back()->with('success', 'Tipe tiket berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $type = TicketType::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:ticket_types,nama,' . $type->id,
        ]);

        $type->update($validated);
        return back()->with('success', 'Tipe tiket berhasil diperbarui');
    }

    public function destroy($id)
    {
        $type = TicketType::findOrFail($id);

        if ($type->tikets()->exists()) {
            return back()->with('error', 'Tipe tiket masih digunakan');
        }

        $type->delete();
        return back()->with('success', 'Tipe tiket berhasil dihapus');
    }
}

