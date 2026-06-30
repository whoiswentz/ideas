<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\IdeaStatus;
use Database\Factories\IdeaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Override;

/***
 * @property string id
 * @property User user
 * @property IdeaStatus status
 * @property array<Step> steps
 * @property array<string> links
 * @property Carbon created_at
 * @property Carbon updated_at
 */
#[Fillable(
    'title',
    'description',
    'status',
    'links',
    'steps',
    'image_path',
)]
class Idea extends Model
{
    /** @use HasFactory<IdeaFactory> */
    use HasFactory, HasUuids;

    #[Override]
    protected $attributes = [
        'status' => IdeaStatus::PENDING,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Step, $this>
     */
    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }

    public static function statusCounts(User $user): Collection
    {
        $counts = $user->ideas()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return collect(IdeaStatus::cases())
            ->mapWithKeys(fn (IdeaStatus $status) => [$status->value => $counts->get($status->value, 0)])
            ->put('all', $user->ideas()->count());
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'links' => AsArrayObject::class,
            'status' => IdeaStatus::class,
        ];
    }
}
