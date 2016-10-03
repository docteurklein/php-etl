<?php

namespace Knp\ETL\Extractor;

use PHPExcel_IOFactory;
use Knp\ETL\ContextInterface;
use Knp\ETL\ExtractorInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class ExcelExtractor implements ExtractorInterface, \Iterator, \Countable
{
    protected $worksheetIterator;
    protected $identifierColumn;
    protected $nbLines;
    protected $headerRowNumber = 1;
    protected $logger;

    public function __construct($filename, $identifierColumn = null, $headerRowNumber = 1, $activeSheet = null, LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
        $this->logger->debug('Extracting Excel', ['filename' => $filename]);

        $excel = PHPExcel_IOFactory::load($filename);

        if (null !== $activeSheet) {
            $excel->setActiveSheetIndex($activeSheet);
        }

        $this->identifierColumn = $identifierColumn;
        $this->headerRowNumber = $headerRowNumber;
        $this->worksheetIterator = $excel->getActiveSheet()->getRowIterator();
        $this->rewind();
    }

    public function extract(ContextInterface $context)
    {
        if (!$this->valid()) {
            return false;
        }

        $data = $this->current();
        $this->next();

        if (null !== $this->identifierColumn) {
            $context->setIdentifier($data[$this->identifierColumn]);
        }

        return $data;
    }

    public function rewind()
    {
        $this->worksheetIterator->rewind();
        $this->worksheetIterator->seek($this->headerRowNumber);
    }

    public function current()
    {
        $row = $this->worksheetIterator->current();
        $cellIterator = $row->getCellIterator();
        $data = array();

        foreach ($cellIterator as $cell) {
            $data[] = $cell->getCalculatedValue();
        }

        return $data;
    }

    public function key()
    {
        return $this->worksheetIterator->key();
    }

    public function next()
    {
        $next = $this->worksheetIterator->next();
        $this->logger->debug('Next Excel element', ['key' => $this->key()]);

        return $next;
    }

    public function valid()
    {
        return $this->worksheetIterator->valid();
    }

    public function count()
    {
        if (null === $this->nbLines) {
            // Store position
            $current = $this->worksheetIterator->key();

            $count = $current - $this->headerRowNumber;
            while ($this->worksheetIterator->valid()) {
                $count += 1;
                $this->worksheetIterator->next();
            }

            // move back to the old position
            $this->worksheetIterator->seek($current);
        }

        return $this->nbLines = $count;
    }
}
