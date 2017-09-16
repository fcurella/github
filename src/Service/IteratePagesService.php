<?php declare(strict_types=1);

namespace ApiClients\Client\Github\Service;

use ApiClients\Foundation\Transport\Service\RequestService;
use Psr\Http\Message\ResponseInterface;
use RingCentral\Psr7\Request;
use Rx\AsyncSchedulerInterface;
use Rx\Observable;
use Rx\Scheduler;
use Rx\Subject\Subject;
use function Kelunik\LinkHeaderRfc5988\parseLinks;

class IteratePagesService
{
    /**
     * @var RequestService
     */
    private $requestService;

    /**
     * @var AsyncSchedulerInterface
     */
    private $scheduler;

    /**
     * @param RequestService          $requestService
     * @param AsyncSchedulerInterface $scheduler
     */
    public function __construct(RequestService $requestService, AsyncSchedulerInterface $scheduler = null)
    {
        $this->scheduler      = $scheduler ?: Scheduler::getAsync();
        $this->requestService = $requestService;
    }

    public function iterate(string $path): Observable
    {
        $paths = new Subject();

        return Observable::of($path, $this->scheduler)
            ->merge($paths)
            ->flatMap(function ($path) {
                return Observable::fromPromise($this->requestService->request(new Request('GET', $path)));
            })
            ->do(function (ResponseInterface $response) use ($paths) {
                if (!$response->hasHeader('link')) {
                    return;
                }

                $parsedLinks = parseLinks($response->getHeaderLine('link'));
                $links = [
                    'next' => $parsedLinks->getByRel('next'),
                    'last' => $parsedLinks->getByRel('last'),
                ];

                if ($links['next'] === null || $links['last'] === null) {
                    return;
                }

                $this->scheduler->schedule(function () use ($paths, $links) {
                    $paths->onNext($links['next']->getUri());
                });
            })
            ->map(function (ResponseInterface $response) {
                return $response->getBody()->getJson();
            });
    }

    private function sendRequest(string $path, Subject $subject)
    {
        $this->requestService->
            request(new Request('GET', $path))->
            then(
                function ($response) use ($subject) {
                    $this->handleResponse($response, $subject);
                },
                function ($error) use ($subject) {
                    $subject->onError($error);
                }
            )
        ;
    }

    private function handleResponse(
        ResponseInterface $response,
        Subject $subject
    ) {
        $subject->onNext($response->getBody()->getJson());

        if ($subject->isDisposed() || !$subject->hasObservers()) {
            $subject->onCompleted();

            return;
        }

        if (!$response->hasHeader('link')) {
            $subject->onCompleted();

            return;
        }

        $links = [
            'next' => false,
            'last' => false,
        ];
        foreach (explode(', ', $response->getHeader('link')[0]) as $link) {
            list($url, $rel) = explode('>; rel="', ltrim(rtrim($link, '"'), '<'));
            if (isset($links[$rel])) {
                $links[$rel] = $url;
            }
        }

        if ($links['next'] === false || $links['last'] === false) {
            $subject->onCompleted();

            return;
        }

        $this->sendRequest($links['next'], $subject);
    }
}
