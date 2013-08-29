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
class CsvExtractor implements ExtractorInterface, \Iterator, \Countable
{
    use LoggerAwareTrait;

    private $csv;
    private $identifierColumn;

    public function __construct($filename, $delimiter = ';', $enclosure = '"', $identifierColumn = null)
    {
        if (null !== $this->logger) {
            $this->logger->debug('extracting from: '.$filename);
        }

        $this->csv = new \SplFileObject($filename);
        $this->csv->setFlags(\SplFileObject::READ_CSV);
        $this->csv->setCsvControl($delimiter, $enclosure);

        $this->identifierColumn = $identifierColumn;
    }

    public function extract(ContextInterface $context)
    {
        $data = $this->csv->fgetcsv();
        if (null !== $this->identifierColumn) {
            $context->setIdentifier($data[$this->identifierColumn]);
        }

        return $data;
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

    public function count()
    {
        $current = $this->csv->key();
        $this->csv->seek($this->csv->getSize());
        $end = $this->csv->key() + 1; // lines started at zéro
        $this->csv->seek($current);

        return $end;
    }

    public function seek($position)
    {
        return $this->csv->seek($position);
    }
}
