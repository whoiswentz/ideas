<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Idea;
use App\Models\User;
use DB;
use Illuminate\Container\Attributes\CurrentUser;
use Throwable;

class UpdateIdea
{
    public function __construct(
        #[CurrentUser] protected User $user,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(Idea $idea, array $attributes): void
    {
        $data = collect($attributes)->only(['title', 'description', 'status', 'links'])->toArray();

        if ($attributes['image'] ?? false) {
            $data['image_path'] = $attributes['image']->store('ideas', 'public');
        }

        DB::transaction(function () use ($attributes, $data, $idea) {
            $idea->update($data);
            $idea->steps()->delete();
            $idea->steps()->createMany($attributes['steps'] ?? []);
        });
    }
}
