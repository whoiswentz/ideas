<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class IdeaImageController extends Controller
{
    public function destroy(Idea $idea): RedirectResponse
    {
        Gate::authorize('workWith', $idea);

        $idea->update(['image_path' => null]);
        Storage::disk('public')->delete($idea->image_path);

        return back();
    }
}
