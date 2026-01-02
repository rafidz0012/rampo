<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $todos = auth()->user()->todos()
            ->when($request->status === 'completed', fn($q) => $q->where('is_completed', true))
            ->when($request->status === 'pending', fn($q) => $q->where('is_completed', false))
            ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
            ->orderBy('is_completed')
            ->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('due_date')
            ->paginate(15);

        $stats = [
            'total' => auth()->user()->todos()->count(),
            'completed' => auth()->user()->todos()->where('is_completed', true)->count(),
            'pending' => auth()->user()->todos()->where('is_completed', false)->count(),
            'overdue' => auth()->user()->todos()
                ->where('is_completed', false)
                ->whereNotNull('due_date')
                ->where('due_date', '<', now())
                ->count(),
        ];

        return view('todos.index', compact('todos', 'stats'));
    }

    public function create()
    {
        return view('todos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'reminder_at' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
        ]);

        auth()->user()->todos()->create($validated);

        return redirect()->route('todos.index')
            ->with('success', 'To-do berhasil ditambahkan!');
    }

    public function edit(Todo $todo)
    {
        return view('todos.edit', compact('todo'));
    }

    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'reminder_at' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
        ]);

        $todo->update($validated);

        return redirect()->route('todos.index')
            ->with('success', 'To-do berhasil diperbarui!');
    }

    public function toggle(Todo $todo)
    {
        $todo->toggle();

        return back()->with('success', $todo->is_completed
            ? 'To-do selesai!'
            : 'To-do dibuka kembali!');
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();

        return redirect()->route('todos.index')
            ->with('success', 'To-do berhasil dihapus!');
    }
}
