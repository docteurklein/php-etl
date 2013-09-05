<?php

namespace Knp\ETL\Transformer;

use Knp\ETL\TransformerInterface;
use Knp\ETL\ContextInterface;

class CallbackTransformer implements TransformerInterface
{
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function transform($data, ContextInterface $context)
    {
        $target = $context->getTransformedData();

        return call_user_func($this->callback, $data, $target);
    }
}