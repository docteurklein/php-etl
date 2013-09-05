<?php

namespace Knp\ETL;

/**
 * @author     Florian Klein <florian.klein@free.fr>
 */
interface TransformerInterface
{
    /**
     * transforms array data into specific representation
     *
     * @param mixed $data the extracted data to transform
     *
     * @return mixed the transformed data
     */
    function transform($data, ContextInterface $context);
}

