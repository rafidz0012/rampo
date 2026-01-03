<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $incomes = auth()->user()->incomes()
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->month, fn($q) => $q->whereMonth('date', $request->month))
            ->latest('date')
            ->paginate(15);

        $categories = ['gaji', 'bonus', 'investasi', 'freelance', 'hadiah', 'other'];
        $totalThisMonth = auth()->user()->incomes()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        return view('incomes.index', compact('incomes', 'categories', 'totalThisMonth'));
    }

    public function create()
    {
        $categories = ['gaji', 'bonus', 'investasi', 'freelance', 'hadiah', 'other'];
        return view('incomes.create', compact('categories'));
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

        auth()->user()->incomes()->create($validated);

        return redirect()->route('incomes.index')
            ->with('success', 'Pemasukan berhasil ditambahkan!');
    }

    public function edit(Income $income)
    {
        $categories = ['gaji', 'bonus', 'investasi', 'freelance', 'hadiah', 'other'];
        return view('incomes.edit', compact('income', 'categories'));
    }

    public function update(Request $request, Income $income)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $income->update($validated);

        return redirect()->route('incomes.index')
            ->with('success', 'Pemasukan berhasil diperbarui!');
    }

    public function destroy(Income $income)
    {
        $income->delete();

        return redirect()->route('incomes.index')
            ->with('success', 'Pemasukan berhasil dihapus!');
    }
}
