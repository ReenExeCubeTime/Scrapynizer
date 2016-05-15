<?php

namespace ReenExe\Scrapynizer\Scrap;

interface PagePathQueueInterface
{
    public function create();

    public function push(array $list);
}
