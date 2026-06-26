<nav class="border-border border-b px-6">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between">
        <div>
            <a href="/">
                <x-logo />
            </a>
        </div>

        <div class="flex gap-x-5">
            @auth
                <form method="POST" action="/logout">
                    @csrf

                    <button data-test="logout-button">Log Out</button>
                </form>
            @endauth

            @guest
                <a href="/register">Register</a>
                <a href="/login" class="btn">Sign In</a>
            @endguest
        </div>
    </div>
</nav>
