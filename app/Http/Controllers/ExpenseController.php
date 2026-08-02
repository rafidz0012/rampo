<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        // Query Utama Pengeluaran Milik User
        $expenses = auth()->user()->expenses()
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->month, fn($q) => $q->whereMonth('date', $request->month))
            ->when($request->year, fn($q) => $q->whereYear('date', $request->year)) // Filter Tahun Baru
            ->latest('date')
            ->paginate(15)
            ->withQueryString(); // Mempertahankan parameter filter saat pindah halaman

        // Kategori Pilihan Anda
        $categories = ['makan', 'transport', 'belanja', 'tagihan', 'hiburan', 'kesehatan', 'pendidikan', 'other'];

        // 1. Data Ringkasan Statistik
        $totalThisMonth = auth()->user()->expenses()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        $totalToday = auth()->user()->expenses()
            ->whereDate('date', now()->today())
            ->sum('amount');

        $averageExpense = auth()->user()->expenses()
            ->avg('amount') ?? 0;

        // 2. Data Grafik Pengeluaran 6 Bulan Terakhir
        $chartLabels = [];
        $chartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            
            $chartLabels[] = $date->translatedFormat('M Y');
            
            $chartData[] = auth()->user()->expenses()
                ->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');
        }

        return view('expenses.index', compact(
            'expenses', 
            'categories', 
            'totalThisMonth', 
            'totalToday', 
            'averageExpense', 
            'chartLabels', 
            'chartData'
        ));
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
        $categories = ['makan', 'transport', 'belanja', 'tagihan', 'hiburan', 'kesehatan', 'pendidikan', 'other'];
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
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
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil dihapus!');
    }
}
