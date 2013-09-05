<?php

namespace Knp\ETL\Transformer;

use Knp\ETL\TransformerInterface;
use Knp\ETL\ContextInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DataMap implements TransformerInterface
{
    private $map = [];

    function __construct(array $map)
    {
        $this->map = $map;
    }

    public function set($input, &$target)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        
        foreach ($this->map as $inputPropertyPath => $output) {
            if (is_array($input)) {
                $path = sprintf('[%s]', $inputPropertyPath);
            } elseif (is_object($input)) {
                $path = $inputPropertyPath;
            } else {
                throw new \InvalidArgumentException('Input should be either an object or array');
            }

            $value = $accessor->getValue($input, $path);

            if (is_object($target)) {
                $path = $output;
            } elseif (is_array($target)) {
                $path = sprintf('[%s]', $output);
            } else {
                throw new \InvalidArgumentException('Target should be either an object or array');
            }

            $accessor->setValue($target, $path, $value);
        }
    }

    public function transform($data, ContextInterface $context)
    {
        $target = $context->getTransformedData();

        $this->set($data, $target);

        return $target;
    }

    public function __invoke($input, $target)
    {
        return $this->set($input, $target);
    }

    public function verifyCount($input)
    {
        if (count($input) !== count($this->map)) {
            throw new \LogicException(sprintf(
                'input does not contain expected size %d, %d given',
                count($this->map),
                count($input)
            ));
        }
    }
}

