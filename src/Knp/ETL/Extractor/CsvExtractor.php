<?php

namespace Knp\ETL\Extractor;

use Symfony\Component\Finder\Finder;

use Knp\ETL\ExtractorInterface;
use Psr\Log\LoggerAwareTrait;
use Knp\ETL\ContextInterface;

/**
 * @author     Florian Klein <florian.klein@free.fr>
 * @TODO just make a LoggableIterator a composition of \Iterator and Logger ?
 */
class CsvExtractor implements ExtractorInterface, \Iterator
{
    use LoggerAwareTrait;

    public function __construct($filename, $delimiter = ';', $enclosure = '"')
    {
        if (null !== $this->logger) {
            $this->logger->debug('extracting from: '.$filename);
        }

        $this->csv = new \SplFileObject($filename);
        $this->csv->setFlags(\SplFileObject::READ_CSV);
        $this->csv->setCsvControl($delimiter, $enclosure);
    }

    public function extract(ContextInterface $context)
    {
        return $this->csv->fgetcsv();
    }

    public function rewind()
    {
        return $this->csv->rewind();
    }

    public function current()
    {
        return $this->csv->current();
    }

    public function key()
    {
        return $this->csv->key();
    }

    public function next()
    {
        $next = $this->csv->next();
        if (null !== $this->logger) {
            $this->logger->debug(sprintf('%s:%d', $this->csv->getBaseName(), $this->key()));
        }

        return $next;
    }

    public function valid()
    {
        return $this->csv->valid();
    }
}
