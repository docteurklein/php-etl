<?php

namespace Knp\ETL\Extractor;

use Symfony\Component\HttpKernel\Log\LoggerInterface;

use Symfony\Component\Finder\Finder;

use Knp\ETL\ExtractorInterface;
use Knp\ETL\ContextInterface;

/**
 * @author     Florian Klein <florian.klein@free.fr>
 */
class OrderedExtractor extends \ArrayIterator implements ExtractorInterface
{
    public function __construct(\Iterator $extractor, callable $orderer)
    {
        $rows = $input = $this->reorder($filename, $orderer);

        parent::__construct($rows);
    }

    private function reorder(\Iterator $extractor, callable $orderer)
    {
        $rows = [];
        // TODO is this necessary?
        foreach ($extractor as $row) {
            $rows[] = $row;
        }

        usort($rows, $orderer);

        return $rows;
    }

    public function extract(ContextInterface $context)
    {
        return $this->current();
    }
}
