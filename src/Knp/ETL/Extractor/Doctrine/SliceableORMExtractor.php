<?php

namespace Knp\ETL\Extractor\Doctrine;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\ETL\ContextInterface;

/**
 * @author Timothée Barray <tim@amicalement-web.net>
 */
class SliceableORMExtractor extends ORMExtractor implements \Countable
{
    private $paginator;

    public function __construct($query, $fetchJoinCollection = true)
    {
        parent::__construct($query);

        $this->paginator = new Paginator($query, $fetchJoinCollection);
    }

    public function extract(ContextInterface $context)
    {
        $current = $this->current();
        $this->next();

        return $current;
    }

    public function count()
    {
        return $this->paginator->count();
    }

    public function slice($offset, $length)
    {
        $this->paginator
            ->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($length)
        ;

        return $this;
    }

    public function getIterator()
    {
        if (null === $this->iterator) {
            $this->iterator = $this->paginator->getIterator();
        }

        return $this->iterator;
    }
}