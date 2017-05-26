<?php declare(strict_types=1);

namespace ApiClients\Tests\Client\Github\Resource\Async\Git;

use ApiClients\Client\Github\ApiSettings;
use ApiClients\Client\Github\Resource\Git\Verification;
use ApiClients\Tools\ResourceTestUtilities\AbstractResourceTest;

class VerificationTest extends AbstractResourceTest
{
    public function getSyncAsync(): string
    {
        return 'Async';
    }

    public function getClass(): string
    {
        return Verification::class;
    }

    public function getNamespace(): string
    {
        return Apisettings::NAMESPACE;
    }
}
