<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $subscriptions = auth()->user()->subscriptions()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('next_billing_date')
            ->paginate(15);

        $totalMonthly = auth()->user()->subscriptions()
            ->where('status', 'active')
            ->get()
            ->sum(fn($sub) => $sub->getMonthlyAmount());

        return view('subscriptions.index', compact('subscriptions', 'totalMonthly'));
    }

    public function create()
    {
        return view('subscriptions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'next_billing_date' => 'required|date',
            'status' => 'required|in:active,cancelled,paused',
            'notes' => 'nullable|string',
        ]);

        auth()->user()->subscriptions()->create($validated);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Langganan berhasil ditambahkan!');
    }

    public function edit(Subscription $subscription)
    {
        $this->authorize('update', $subscription);
        return view('subscriptions.edit', compact('subscription'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $this->authorize('update', $subscription);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'next_billing_date' => 'required|date',
            'status' => 'required|in:active,cancelled,paused',
            'notes' => 'nullable|string',
        ]);

        $subscription->update($validated);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Langganan berhasil diperbarui!');
    }

    public function destroy(Subscription $subscription)
    {
        $this->authorize('delete', $subscription);
        $subscription->delete();

        return redirect()->route('subscriptions.index')
            ->with('success', 'Langganan berhasil dihapus!');
    }
}
