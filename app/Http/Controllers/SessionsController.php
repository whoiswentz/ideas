<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class SessionsController extends Controller
{
    public function create(): Factory|View
    {
        return view('auth.login');
    }
}
