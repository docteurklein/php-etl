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
    private $nbLines;

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
        $data = $this->csv->current();
        if (null !== $this->identifierColumn) {
            $context->setIdentifier($data[$this->identifierColumn]);
        }
        $this->csv->next();

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
        if (null === $this->nbLines) {
            // Store flags and position
            $flags = $this->csv->getFlags();
            $current = $this->csv->key();
     
            // Prepare count by resetting flags as READ_CSV for example make the trick very slow
            $this->csv->setFlags(null);
     
            // Go to the larger INT we can as seek will not throw exception, errors, notice if we go beyond the bottom line
            $this->csv->seek(PHP_INT_MAX);
     
            // We store the key position
            // As key starts at 0, we add 1
            $this->nbLines = $this->csv->key() + 1;
     
            // We move to old position
            // As seek method is longer with line number < to the max line number, it is better to count at the beginning of iteration
            $this->csv->seek($current);
     
            // Re set flags
            $this->csv->setFlags($flags);
        }

        return $this->nbLines; 
    }

    public function seek($position)
    {
        return $this->csv->seek($position);
    }
}
