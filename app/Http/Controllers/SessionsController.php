<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateSessionRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function create(): Factory|View
    {
        return view('auth.login');
    }

    public function store(CreateSessionRequest $request): Redirector|RedirectResponse
    {
        $attributes = $request->validated();
        if (! Auth::attempt($attributes)) {
            return redirect()
                ->back()
                ->withErrors(['password' => 'Wrong credentials'])
                ->withInput();
        }
        $request->session()->regenerate();

        return redirect()
            ->intended('/')
            ->with('success', 'You have successfully logged in');
    }

    public function destroy(): Redirector|RedirectResponse
    {
        Auth::logout();

        return redirect('/');
    }
}
