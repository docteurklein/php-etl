<?php

namespace Knp\ETL\Loader\Doctrine;

use Psr\Log\LoggerAwareTrait;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\ETL\ContextInterface;
use Knp\ETL\Context\Doctrine\ORMContext;

class ORMLoader
{
    use LoggerAwareTrait;

    private $counter = 0;
    private $flushEvery;
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine, $flushEvery = 100)
    {
        $this->doctrine = $doctrine;
        $this->flushEvery = $flushEvery;
    }

    public function load($entity, ContextInterface $context)
    {
        $this->doctrine->getManager()->persist($entity);

        $shouldFlush = $shouldClear = false;
        if ($context instanceof ORMContext) {
            $shouldFlush = $context->shouldFlush();
            $context->shouldFlush(false); // TODO really ?

            $shouldClear = $context->shouldClear();
            $context->shouldClear(false); // TODO really ?
        }

        $this->counter++;

        if ($this->counter % $this->flushEvery === 0 || $shouldFlush) {
            $this->flush($context);
        }

        if ($this->counter % $this->flushEvery === 0 || $shouldClear) {
            $this->clear($context);
        }
    }

    public function flush(ContextInterface $context)
    {
        $this->doctrine->getManager()->flush();
        if (null !== $this->logger) {
            $this->logger->debug(sprintf('flush after %d persist hits', $this->counter));
        }
    }

    public function clear(ContextInterface $context)
    {
        $this->doctrine->getManager()->clear();
        if (null !== $this->logger) {
            $this->logger->debug(sprintf('clear after %d persist hits', $this->counter));
        }
    }
}

