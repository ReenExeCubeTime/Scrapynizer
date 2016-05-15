<?php

namespace ReenExe\Scrapynizer;

interface PathCollectionRepositoryInterface
{
    /**
     * @param $limit
     * @return array
     */
    public function getNext($limit);

    /**
     * @param $path
     */
    public function exclude($path);
}
