<?php declare(strict_types=1);

namespace ApiClients\Client\Github\Resource\Async\Contents;

use ApiClients\Client\Github\CommandBus\Command\RefreshCommand;
use ApiClients\Client\Github\CommandBus\Command\Repository\Contents\FileUploadCommand;
use ApiClients\Client\Github\Resource\Contents\File as BaseFile;
use React\Promise\CancellablePromiseInterface;
use React\Stream\ReadableStreamInterface;

class File extends BaseFile
{
    public function refresh(): CancellablePromiseInterface
    {
        return $this->handleCommand(new RefreshCommand($this));
    }

    public function update(ReadableStreamInterface $stream, string $commitMessage = null)
    {
        if ($commitMessage === null) {
            $commitMessage = 'Updated ' . $this->name;
        }

        return $this->handleCommand(new FileUploadCommand(
            $this->repository_fullname,
            $commitMessage,
            $this->url,
            $this->sha,
            $stream
        ));
    }
}
