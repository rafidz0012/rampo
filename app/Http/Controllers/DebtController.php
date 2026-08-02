<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil daftar hutang milik user yang sedang login
        $debts = Debt::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('debt.index', compact('debts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('debt.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'creditor_name' => 'required|string|max:255',
            'total'         => 'required|numeric|min:0',
            'due_date'      => 'nullable|date',
            'note'          => 'nullable|string',
        ]);

        // Secara otomatis mengisi user_id, status awal 'pending',
        // dan sisa hutang (remaining_amount) disamakan dengan total awal
        Debt::create([
            'user_id'          => auth()->id(),
            'title'            => $validated['title'],
            'creditor_name'    => $validated['creditor_name'],
            'total'            => $validated['total'],
            'remaining_amount' => $validated['total'],
            'due_date'         => $validated['due_date'] ?? null,
            'status'           => 'pending',
            'note'             => $validated['note'] ?? null,
        ]);

        return redirect()
            ->route('debt.index')
            ->with('success', 'Data hutang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Debt $debt)
    {
        // Keamanan: pastikan hutang milik user yang sedang login
        $this->authorizeOwner($debt);

        return view('debts.show', compact('debt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Debt $debt)
    {
        $this->authorizeOwner($debt);

        return view('debt.edit', compact('debt'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Debt $debt)
    {
        $this->authorizeOwner($debt);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'creditor_name'    => 'required|string|max:255',
            'total'            => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0|lte:total',
            'due_date'         => 'nullable|date',
            'status'           => 'required|in:pending,partial,paid',
            'note'             => 'nullable|string',
        ]);

        // Otomatis ubah status jika remaining_amount diubah ke 0
        if ($validated['remaining_amount'] == 0) {
            $validated['status'] = 'paid';
        } elseif ($validated['remaining_amount'] < $validated['total']) {
            $validated['status'] = 'partial';
        }

        $debt->update($validated);

        return redirect()
            ->route('debt.index')
            ->with('success', 'Data hutang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Debt $debt)
    {
        $this->authorizeOwner($debt);

        $debt->delete();

        return redirect()
            ->route('debt.index')
            ->with('success', 'Data hutang berhasil dihapus.');
    }

    /**
     * Helper untuk keamanan (Pencegahan akses data milik akun lain)
     */
    private function authorizeOwner(Debt $debt)
    {
        if ($debt->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}   