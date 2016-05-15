<?php

namespace ReenExe\Scrapynizer\Analyzer;

interface ContentAnalyzerInterface
{
    /**
     * @param $nextPath
     * @param $html
     * @return mixed
     */
    public function analyze($nextPath, $html);
}
