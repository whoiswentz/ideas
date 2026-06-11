<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\StepFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string id
 */
#[Fillable('description')]
class Step extends Model
{
    /** @use HasFactory<StepFactory> */
    use HasFactory, HasUuids;

    public function idea(): BelongsTo
    {
        return $this->belongsTo(Idea::class);
    }
}
