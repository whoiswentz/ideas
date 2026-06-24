<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Laravel\Nightwatch\Facades\Nightwatch;
use Laravel\Nightwatch\Records\Query;
use Override;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict();
        Model::automaticallyEagerLoadRelationships();

        Nightwatch::rejectQueries(fn (Query $query): bool => self::isInfrastructureQuery($query->sql));
    }

    /**
     * Determine whether a SQL statement targets a framework infrastructure
     * table rather than application data.
     *
     * Sessions, cache, and the queue all run on the database in this
     * application, so they touch these tables on nearly every request.
     * Rejecting them keeps Nightwatch traces focused on application queries.
     */
    public static function isInfrastructureQuery(string $sql): bool
    {
        $frameworkTables = [
            '"sessions"',
            '"cache"',
            '"cache_locks"',
            '"jobs"',
            '"job_batches"',
            '"failed_jobs"',
        ];

        return array_any($frameworkTables, fn ($table) => str_contains($sql, (string) $table));
    }
}
