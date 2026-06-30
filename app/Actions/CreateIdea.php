<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use DB;
use Illuminate\Container\Attributes\CurrentUser;
use Throwable;

class CreateIdea
{
    public function __construct(
        #[CurrentUser] protected User $user,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(array $attributes): void
    {
        $data = collect($attributes)->only(['title', 'description', 'status', 'links'])->toArray();

        if ($attributes['image'] ?? false) {
            $data['image_path'] = $attributes['image']->store('ideas', 'public');
        }

        DB::transaction(function () use ($attributes, $data) {
            $idea = $this->user->ideas()->create($data);
            $seps = collect($attributes['steps']->steps)->map(fn (string $step) => ['description' => $step]);
            $idea->steps()->createMany($seps);
        });
    }
}
