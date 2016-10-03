<?php

namespace Knp\ETL\Loader\Doctrine;

use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\ETL\ContextInterface;
use Knp\ETL\Context\Doctrine\ORMContext;
use Knp\ETL\LoaderInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ORMLoader implements LoaderInterface
{
    private $counter = 0;
    private $flushEvery;
    private $doctrine;
    private $entityClass;
    private $logger;

    public function __construct(ManagerRegistry $doctrine, $flushEvery = 100, LoggerInterface $logger = null)
    {
        $this->doctrine = $doctrine;
        $this->flushEvery = $flushEvery;
        $this->logger = $logger ?: new NullLogger();
    }

    public function load($entity, ContextInterface $context)
    {
        if (null === $this->entityClass) {
            $this->entityClass = get_class($entity);
        }
        
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
        $this->logger->debug('Doctrine flush', ['persist_hits' => $this->counter]);
    }

    public function clear(ContextInterface $context)
    {
        $this->doctrine->getManager()->clear($this->entityClass);
        $this->logger->debug('Doctrine clean', ['persist_hits' => $this->counter]);
    }
}

