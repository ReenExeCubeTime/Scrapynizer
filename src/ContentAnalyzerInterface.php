<?php

namespace ReenExe\Scrapynizer;

interface ContentAnalyzerInterface
{
    /**
     * @param $nextPath
     * @param $html
     * @return mixed
     */
    public function analyze($nextPath, $html);
}
