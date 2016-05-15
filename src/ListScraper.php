<?php

namespace ReenExe\Scrapynizer;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\DomCrawler\Crawler;

class ListScraper
{
    const STATUS_PROGRESS = 0;

    const STATUS_END = 1;

    /**
     * @var ListContentRepositoryInterface
     */
    private $repository;

    /**
     * @var PaginationHunterInterface
     */
    private $pager;

    /**
     * @var ListContentAnalyzerInterface
     */
    private $analyzer;

    /**
     * @var Client
     */
    private $client;

    public function process($limit)
    {
        if ($last = $this->repository->getLast()) {
            $nextPath = $this->pager->getNextPage(new Crawler($last));

            if (empty($nextPath)) {
                return self::STATUS_END;
            }

        } else {
            $nextPath = $this->pager->getFirstPage();
        }

        do {
            try {
                $html =  $this->client->get($nextPath)->getBody()->getContents();
            } catch (ClientException $e) {
                return self::STATUS_END;
            }

            $crawler = new Crawler($html);

            $this->repository->save($nextPath, $html);

            $this->analyzer->analyze($nextPath, $html, $crawler);

            $nextPath = $this->pager->getNextPage($crawler);
        } while (--$limit && $nextPath);

        return self::STATUS_PROGRESS;
    }
}
