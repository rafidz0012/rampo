<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Subscription;
use App\Models\Todo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $currentMonth = Carbon::now();

        // Statistik keuangan bulan ini
        $monthlyIncome = $user->incomes()
            ->whereMonth('date', $currentMonth->month)
            ->whereYear('date', $currentMonth->year)
            ->sum('amount');

        $monthlyExpense = $user->expenses()
            ->whereMonth('date', $currentMonth->month)
            ->whereYear('date', $currentMonth->year)
            ->sum('amount');

        $balance = $monthlyIncome - $monthlyExpense;

        // Active subscriptions monthly cost
        $monthlySubscriptions = $user->subscriptions()
            ->where('status', 'active')
            ->get()
            ->sum(fn($sub) => $sub->getMonthlyAmount());

        // Upcoming subscriptions (next 7 days)
        $upcomingBills = $user->subscriptions()
            ->where('status', 'active')
            ->whereBetween('next_billing_date', [now(), now()->addDays(7)])
            ->orderBy('next_billing_date')
            ->get();

        // Pending todos
        $pendingTodos = $user->todos()
            ->where('is_completed', false)
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // Overdue todos count
        $overdueTodos = $user->todos()
            ->where('is_completed', false)
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->count();

        // Recent notes
        $recentNotes = $user->notes()
            ->latest()
            ->limit(3)
            ->get();

        // Recent transactions
        $recentIncomes = $user->incomes()->latest()->limit(5)->get();
        $recentExpenses = $user->expenses()->latest()->limit(5)->get();

        return view('dashboard', compact(
            'monthlyIncome',
            'monthlyExpense',
            'balance',
            'monthlySubscriptions',
            'upcomingBills',
            'pendingTodos',
            'overdueTodos',
            'recentNotes',
            'recentIncomes',
            'recentExpenses'
        ));
    }
}
