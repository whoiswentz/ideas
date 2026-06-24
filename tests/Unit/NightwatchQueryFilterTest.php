<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;

mutates(AppServiceProvider::class);

it('rejects framework infrastructure queries', function (string $sql) {
    expect(AppServiceProvider::isInfrastructureQuery($sql))->toBeTrue();
})->with([
    'session read' => ['select * from "sessions" where "id" = ?'],
    'session write' => ['insert into "sessions" ("payload") values (?)'],
    'cache read' => ['select * from "cache" where "key" = ?'],
    'cache lock' => ['delete from "cache_locks" where "key" = ?'],
    'queue pop' => ['select * from "jobs" where "queue" = ? order by "id" asc limit 1'],
    'job batch' => ['update "job_batches" set "pending_jobs" = ? where "id" = ?'],
    'failed job' => ['insert into "failed_jobs" ("connection") values (?)'],
]);

it('keeps application queries', function (string $sql) {
    expect(AppServiceProvider::isInfrastructureQuery($sql))->toBeFalse();
})->with([
    'ideas listing' => ['select * from "ideas" where "user_id" = ?'],
    'users lookup' => ['select * from "users" where "email" = ? limit 1'],
    // A real column named like a framework table should not be filtered.
    'unquoted substring' => ['select sessions_count from "ideas"'],
]);
