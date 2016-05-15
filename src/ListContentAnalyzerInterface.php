<?php

namespace ReenExe\Scrapynizer;

use Symfony\Component\DomCrawler\Crawler;

interface ListContentAnalyzerInterface
{
    /**
     * @param $nextPath
     * @param $html
     * @param Crawler $crawler
     * @return mixed
     */
    public function analyze($nextPath, $html, Crawler $crawler);
}
