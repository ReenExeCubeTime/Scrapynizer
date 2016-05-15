<?php

namespace ReenExe\Scrapynizer\Scrap;

interface PageProcessInterface
{
    public function getNextList($limit);

    public function exclude($path);
}
