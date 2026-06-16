<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class RegisterUserController extends Controller
{
    public function create(Request $request): Factory|View
    {
        return view('auth.register');
    }

    public function store(CreateUserRequest $request): RedirectResponse|Redirector
    {
        $user = User::create($request->validated());
        Auth::login($user);

        return redirect('/')->with('success', 'Registration successful!');
    }
}
