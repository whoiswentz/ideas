<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\StepFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

/**
 * @property string id
 * @property string description
 * @property bool completed
 * @property Idea idea
 */
#[Fillable(
    'description',
    'completed'
)]
class Step extends Model
{
    /** @use HasFactory<StepFactory> */
    use HasFactory, HasUuids;

    public function idea(): BelongsTo
    {
        return $this->belongsTo(Idea::class);
    }

    /**
     * @return array<string, string>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'completed' => 'boolean',
        ];
    }
}
