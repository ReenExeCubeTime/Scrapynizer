<?php

namespace ReenExe\Scrapynizer;

use GuzzleHttp\Exception\ClientException;
use Symfony\Component\DomCrawler\Crawler;

class ListScraper extends AbstractScraper
{
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
