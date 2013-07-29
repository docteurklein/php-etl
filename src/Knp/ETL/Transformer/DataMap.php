<?php

namespace Knp\ETL\Transformer;

use Knp\ETL\TransformerInterface;
use Knp\ETL\ContextInterface;

class DataMap implements TransformerInterface
{
    private $map = [];

    function __construct(array $map)
    {
        $this->map = $map;
    }

    public function set($input, $target)
    {
        foreach ($this->map as $inputPropertyPath => $output) {
            if (is_object($input)) {
                $inputValue = $input->$inputPropertyPath();
            } elseif (is_array($input)) {
                $inputValue = $input[$inputPropertyPath];
            } else {
                throw new \InvalidArgumentException('Input should be either an object or array');
            }

            if (is_array($output)) {
                $outputPropertyPath = array_keys($output)[0];
                $transformedValue   = $output[$outputPropertyPath]($inputValue, $input);
            } else {
                $outputPropertyPath = $output;
                $transformedValue = $inputValue;
            }

            if (is_object($target)) {
                $target->$outputPropertyPath($transformedValue);
            } elseif (is_array($target)) {
                $target[$outputPropertyPath] = $transformedValue;
            } else {
                throw new \InvalidArgumentException('Target should be either an object or array');
            }
        }
    }

    public function transform(array $data, ContextInterface $context)
    {
        $target = [];

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

