<?php declare(strict_types=1);

namespace ApiClients\Client\Github\Resource\Async;

use ApiClients\Client\Github\CommandBus\Command\RefreshCommand;
use ApiClients\Client\Github\CommandBus\Command\Repository\AddLabelCommand;
use ApiClients\Client\Github\CommandBus\Command\Repository\BranchesCommand;
use ApiClients\Client\Github\CommandBus\Command\Repository\CommitsCommand;
use ApiClients\Client\Github\CommandBus\Command\Repository\CommunityHealthCommand;
use ApiClients\Client\Github\CommandBus\Command\Repository\Contents\FileUploadCommand;
use ApiClients\Client\Github\CommandBus\Command\Repository\ContentsCommand;
use ApiClients\Client\Github\CommandBus\Command\Repository\LabelsCommand;
use ApiClients\Client\Github\CommandBus\Command\Repository\LanguagesCommand;
use ApiClients\Client\Github\CommandBus\Command\Repository\ReleasesCommand;
use ApiClients\Client\Github\CommandBus\Command\Repository\TagsCommand;
use ApiClients\Client\Github\Resource\Repository as BaseRepository;
use React\Promise\PromiseInterface;
use React\Stream\ReadableStreamInterface;
use Rx\Observable;
use Rx\ObservableInterface;
use function ApiClients\Tools\Rx\unwrapObservableFromPromise;

class Repository extends BaseRepository
{
    public function refresh(): PromiseInterface
    {
        return $this->handleCommand(
            new RefreshCommand($this)
        );
    }

    public function branches(): ObservableInterface
    {
        return unwrapObservableFromPromise($this->handleCommand(
            new BranchesCommand($this->fullName())
        ));
    }

    public function commits(): ObservableInterface
    {
        return unwrapObservableFromPromise($this->handleCommand(
            new CommitsCommand($this->fullName())
        ));
    }

    public function labels(): ObservableInterface
    {
        return unwrapObservableFromPromise($this->handleCommand(
            new LabelsCommand($this->fullName())
        ));
    }

    public function addLabel(string $name, string $colour): PromiseInterface
    {
        return $this->handleCommand(
            new AddLabelCommand($this->fullName(), $name, $colour)
        );
    }

    public function contents(string $path = '/'): Observable
    {
        return unwrapObservableFromPromise(
            $this->handleCommand(
                new ContentsCommand($this->fullName(), $path)
            )
        );
    }

    public function communityHealth(): PromiseInterface
    {
        return $this->handleCommand(
            new CommunityHealthCommand($this->fullName())
        );
    }

    public function tags(): ObservableInterface
    {
        return unwrapObservableFromPromise($this->handleCommand(
            new TagsCommand($this->fullName())
        ));
    }

    public function releases(): ObservableInterface
    {
        return unwrapObservableFromPromise($this->handleCommand(
            new ReleasesCommand($this->fullName())
        ));
    }

    public function languages(): PromiseInterface
    {
        return $this->handleCommand(
            new LanguagesCommand($this->fullName())
        );
    }

    public function addFile(
        string $filename,
        ReadableStreamInterface $stream,
        string $commitMessage = '',
        string $branch = ''
    ): PromiseInterface {
        if ($commitMessage === '') {
            $commitMessage = 'Update ' . $this->name;
        }

        return $this->handleCommand(new FileUploadCommand(
            $this->full_name,
            $commitMessage,
            '/repos/' . $this->full_name . '/contents/' . $filename,
            '',
            $branch,
            $stream
        ));
    }
}
