<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vacancy extends Model
{
    protected $fillable = [
        'company_id',
        'created_by',
        'title',
        'description',
        'location',
        'employment_type',
        'status',
        'published_at',
        'expiration_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expiration_date' => 'date'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
