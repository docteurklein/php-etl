<?php

namespace Knp\ETL\Extractor;

use Symfony\Component\Finder\Finder;

use Knp\ETL\ExtractorInterface;
use Knp\ETL\ContextInterface;

/**
 * @author     Florian Klein <florian.klein@free.fr>
 */
class CachedExtractor extends \ArrayIterator implements ExtractorInterface
{
    public function __construct(\Iterator $extractor)
    {
        parent::__construct(iterator_to_array($extractor));
    }

    public function extract(ContextInterface $context)
    {
        return $this->current();
    }
}
