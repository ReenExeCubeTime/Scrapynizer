<?php

namespace ReenExe\Scrapynizer\Scraper;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use ReenExe\Scrapynizer\Analyzer\ListContentAnalyzerInterface;
use ReenExe\Scrapynizer\Pager\PaginationHunterInterface;
use ReenExe\Scrapynizer\Repository\ListContentRepositoryInterface;
use Symfony\Component\DomCrawler\Crawler;

class ListScraper extends AbstractScraper
{
    /**
     * @var ListContentRepositoryInterface
     */
    protected $repository;

    /**
     * @var PaginationHunterInterface
     */
    protected $pager;

    /**
     * @var ListContentAnalyzerInterface
     */
    protected $analyzer;

    public function __construct(
        Client $client,
        PaginationHunterInterface $pager,
        ListContentRepositoryInterface $repository,
        ListContentAnalyzerInterface $analyzer
    ) {
        $this->client = $client;
        $this->pager = $pager;
        $this->repository = $repository;
        $this->analyzer = $analyzer;
    }

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
