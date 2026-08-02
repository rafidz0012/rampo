<?php

namespace App\Http\Controllers;

use App\Models\Receivable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceivableController extends Controller
{
    /**
     * Tampilkan daftar piutang pengguna.
     */
    public function index(Request $request)
    {
        $query = Receivable::where('user_id', Auth::id());

        // Pencarian berdasarkan judul atau nama peminjam
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('debtor_name', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $receivables = $query->latest()->paginate(10)->withQueryString();

        // Hitung total sisa tagihan piutang milik user
        $totalRemaining = Receivable::where('user_id', Auth::id())
            ->where('status', '!=', 'paid')
            ->sum('remaining_amount');

        return view('receivables.index', compact('receivables', 'totalRemaining'));
    }

    /**
     * Tampilkan form untuk membuat piutang baru.
     */
    public function create()
    {
        return view('receivables.create');
    }

    /**
     * Simpan piutang baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'debtor_name'  => 'required|string|max:255',
            'total'        => 'required|numeric|min:0',
            'due_date'     => 'nullable|date',
            'note'         => 'nullable|string',
        ]);

        // Saat pertama kali dibuat, sisa piutang sama dengan total piutang
        $validated['user_id']          = Auth::id();
        $validated['remaining_amount'] = $validated['total'];
        $validated['status']           = 'pending';

        Receivable::create($validated);

        return redirect()->route('receivables.index')
            ->with('success', 'Data piutang berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk mengedit piutang.
     */
    public function edit(Receivable $receivable)
    {
        $this->authorizeOwner($receivable);

        return view('receivables.edit', compact('receivable'));
    }

    /**
     * Perbarui data piutang di database.
     */
    public function update(Request $request, Receivable $receivable)
    {
        $this->authorizeOwner($receivable);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'debtor_name'      => 'required|string|max:255',
            'total'            => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0|max:' . $request->total,
            'status'           => 'required|in:pending,partial,paid',
            'due_date'         => 'nullable|date',
            'note'             => 'nullable|string',
        ]);

        // Otomatisasi ubah status ke 'paid' jika sisa tagihan sudah 0
        if ($validated['remaining_amount'] == 0) {
            $validated['status'] = 'paid';
        }

        $receivable->update($validated);

        return redirect()->route('receivables.index')
            ->with('success', 'Data piutang berhasil diperbarui.');
    }

    /**
     * Hapus data piutang dari database.
     */
    public function destroy(Receivable $receivable)
    {
        $this->authorizeOwner($receivable);

        $receivable->delete();

        return redirect()->route('receivables.index')
            ->with('success', 'Data piutang berhasil dihapus.');
    }

    /**
     * Helper untuk memastikan user hanya bisa mengelola piutang miliknya sendiri.
     */
    private function authorizeOwner(Receivable $receivable): void
    {
        if ($receivable->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
    }
}