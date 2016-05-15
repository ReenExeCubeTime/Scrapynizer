<?php

namespace ReenExe\Scrapynizer\Scrap;

interface ProfileListContentStorageInterface
{
    /**
     * @return mixed
     */
    public function create();

    /**
     * @return string
     */
    public function getLast();

    /**
     * @param $path
     * @param $value
     * @return mixed
     */
    public function save($path, $value);
}
