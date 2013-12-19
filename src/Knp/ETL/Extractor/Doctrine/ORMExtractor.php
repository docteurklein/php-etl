<?php

namespace Knp\ETL\Extractor\Doctrine;

use Knp\ETL\ContextInterface;
use Knp\ETL\ExtractorInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * @author Timothée Barray <tim@amicalement-web.net>
 */
class ORMExtractor implements ExtractorInterface, \Iterator
{
    use LoggerAwareTrait;

    private $query;

    protected $iterator;

    /**
     * Could be a Query or a QueryBuilder
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    public function extract(ContextInterface $context)
    {
        $current = $this->current();
        $this->next();

        return $current[0];
    }

    public function rewind()
    {
        return $this->getIterator()->rewind();
    }

    public function current()
    {
        return $this->getIterator()->current();
    }

    public function key()
    {
        return $this->getIterator()->key();
    }

    public function next()
    {
        $next = $this->getIterator()->next();

        if (null !== $this->logger) {
            $this->logger->debug($this->getQuery()->getSql());
        }

        return $next;
    }

    public function valid()
    {
        return $this->getIterator()->valid();
    }

    public function getIterator()
    {
        if (null === $this->iterator) {
            $this->iterator = $this->getQuery()->iterate();
        }

        return $this->iterator;
    }

    public function getQuery()
    {
        if ($this->query instanceof \Doctrine\ORM\QueryBuilder) {
            return $this->query->getQuery();        
        }

        return $this->query;
    }
}