<?php

namespace Knp\ETL\Extractor;

use Symfony\Component\Finder\Finder;

use Knp\ETL\Extractor\CsvExtractor;

/**
 * @author     Florian Klein <florian.klein@free.fr>
 */
class RegexFilenameCsvExtractor extends CsvExtractor
{
    public function __construct($csvPath, $regex, $delimiter = ';', $enclosure = '"')
    {
        $filename = null;
        $files = Finder::create()
            ->files()
            ->followLinks()
            ->sortByName()
            ->name($regex)
            ->in($csvPath)
            ->depth('== 0')
        ;

        foreach ($files as $csv) {
            $filename = (string) $csv;
        }

        parent::__construct($filename, $delimiter, $enclosure);
    }
}
