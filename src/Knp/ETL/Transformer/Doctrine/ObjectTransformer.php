<?php

namespace Knp\ETL\Transformer\Doctrine;

use Knp\ETL\TransformerInterface;
use Knp\ETL\ContextInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Psr\Log\LoggerAwareTrait;
use Knp\ETL\Transformer\DataMap;

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
    }

    public function transform($data, ContextInterface $context)
    {
        $this->mapper->verifyCount($data);

        $id = $context->getIdentifier();
        if (null !== $this->logger) {
            $this->logger->info(sprintf('Transforming data with id #%s', $id));
        }

        $object = $this->doctrine->getRepository($this->className)->find($id);

        if (null === $object) {
            //TODO use a configurable factory here
            $object = new $this->className;
            if (null !== $this->logger) {
                $this->logger->info(sprintf('Creating new object "%s"', $this->className));
            }
        }

        $this->mapper->set($data, $object);

        return $object;
    }
}
