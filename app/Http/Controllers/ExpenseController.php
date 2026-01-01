<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expenses = auth()->user()->expenses()
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->month, fn($q) => $q->whereMonth('date', $request->month))
            ->latest('date')
            ->paginate(15);

        $categories = ['makan', 'transport', 'belanja', 'tagihan', 'hiburan', 'kesehatan', 'pendidikan', 'other'];
        $totalThisMonth = auth()->user()->expenses()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        return view('expenses.index', compact('expenses', 'categories', 'totalThisMonth'));
    }

    public function create()
    {
        $categories = ['makan', 'transport', 'belanja', 'tagihan', 'hiburan', 'kesehatan', 'pendidikan', 'other'];
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        auth()->user()->expenses()->create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);
        $categories = ['makan', 'transport', 'belanja', 'tagihan', 'hiburan', 'kesehatan', 'pendidikan', 'other'];
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil diperbarui!');
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil dihapus!');
    }
}
