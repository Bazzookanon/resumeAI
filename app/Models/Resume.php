<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Resume extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'candidate_name',
        'candidate_email',
        'file_path',
        'file_name',
        'status',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function screening(): HasOne
    {
        return $this->hasOne(Screening::class);
    }
}
