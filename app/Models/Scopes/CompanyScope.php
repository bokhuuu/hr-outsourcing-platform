<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class CompanyScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();

        if (!$user) {
            return;
        }

        if ($user->role === 'hr' || $user->role === 'admin') {
            return;
        }

        $companyId = DB::table('employees')
            ->where('user_id', $user->id)
            ->value('company_id');

        if (!$companyId) {
            return;
        }

        $builder->where('company_id', $companyId);
    }
}
