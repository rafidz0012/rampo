<?php

namespace App\Providers;

use App\Models\Document;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Note;
use App\Models\Subscription;
use App\Models\Todo;
use App\Policies\DocumentPolicy;
use App\Policies\ExpensePolicy;
use App\Policies\IncomePolicy;
use App\Policies\NotePolicy;
use App\Policies\SubscriptionPolicy;
use App\Policies\TodoPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Income::class, IncomePolicy::class);
        Gate::policy(Expense::class, ExpensePolicy::class);
        Gate::policy(Subscription::class, SubscriptionPolicy::class);
        Gate::policy(Note::class, NotePolicy::class);
        Gate::policy(Todo::class, TodoPolicy::class);
        Gate::policy(Document::class, DocumentPolicy::class);
    }
}
