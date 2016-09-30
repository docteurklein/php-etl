<?php

namespace Knp\ETL\Transformer\Doctrine;

use Knp\ETL\TransformerInterface;
use Knp\ETL\ContextInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\ETL\Transformer\DataMap;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class ObjectTransformer implements TransformerInterface
{
    use LoggerAwareTrait;

    private $className;
    private $mapper;
    private $doctrine;

    public function __construct($className, DataMap $mapper, ManagerRegistry $doctrine)
    {
        $this->className = $className;
        $this->mapper = $mapper;
        $this->doctrine = $doctrine;
        $this->logger = new NullLogger();
    }

    public function transform($data, ContextInterface $context)
    {
        $this->mapper->verifyCount($data);

        $id = $context->getIdentifier();
        $this->logger->info('Transforming data', ['id' => $id]);

        $object = $this->doctrine->getRepository($this->className)->find($id);

        if (null === $object) {
            //TODO use a configurable factory here
            $object = new $this->className;
            $this->logger->info('Creating new object', ['class' => $this->className]);
        }

        $this->mapper->set($data, $object);

        return $object;
    }
}
