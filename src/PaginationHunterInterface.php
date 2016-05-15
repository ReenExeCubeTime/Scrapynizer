<?php

namespace ReenExe\Scrapynizer;

use Symfony\Component\DomCrawler\Crawler;

interface PaginationHunterInterface
{
    /**
     * @return string
     */
    public function getFirstPage();

    /**
     * @param Crawler $crawler
     * @return string|false
     */
    public function getNextPage(Crawler $crawler);
}
