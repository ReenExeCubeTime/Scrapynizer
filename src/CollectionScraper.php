<?php

namespace ReenExe\Scrapynizer;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

class CollectionScraper extends AbstractScraper
{
    /**
     * @var ContentAnalyzerInterface
     */
    private $analyzer;

    /**
     * @var PathCollectionRepositoryInterface
     */
    private $repository;

    protected function process($limit)
    {
        $pages = $this->repository->getNext($limit);

        if (empty($pages)) {
            return self::STATUS_END;
        }

        /* @var $promises PromiseInterface[] */
        $promises = [];

        foreach ($pages as $path) {
            $promises[] = $this->client
                ->getAsync($path)
                ->then(function (ResponseInterface $response) use ($path) {
                    $html = $response->getBody()->getContents();
                    $this->analyzer->analyze($path, $html);
                    $this->repository->exclude($path);
                });
        }

        do {
            foreach ($promises as $key => $promise) {
                if ($promise->getState() === PromiseInterface::FULFILLED) {
                    unset($promises[$key]);
                } else {
                    $promise->wait();
                }
            }
        } while ($promises);

        return self::STATUS_PROGRESS;
    }
}