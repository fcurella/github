<?php declare(strict_types=1);
use ApiClients\Client\Github\AsyncClient;
use ApiClients\Client\Github\Resource\Async\Repository;
use ApiClients\Client\Github\Resource\Async\User;
use function ApiClients\Foundation\resource_pretty_print;
use React\EventLoop\Factory;

require \dirname(__DIR__) . \DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$loop = Factory::create();

$client = AsyncClient::create($loop, require 'resolve_token.php');

$client->user($argv[1] ?? 'php-api-clients')->then(function (User $user) use ($argv) {
    resource_pretty_print($user);

    return $user->repository($argv[2] ?? 'github');
})->then(function (Repository $repository) {
    resource_pretty_print($repository, 1, true);
    $repository->commits()->take(1)->flatMap(function (Repository\Commit $commit) {
        resource_pretty_print($commit, 2, true);

        return $commit->checks();
    })->subscribe(function (Repository\Commit\Check $check) {
        resource_pretty_print($check, 3, true);
    }, 'display_throwable');
})->done(null, 'display_throwable');

$loop->run();

displayState($client->getRateLimitState());
