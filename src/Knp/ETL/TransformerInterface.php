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
     * @param array $data the extracted data to transform
     *
     * @return mixed the transformed data
     */
    function transform(array $data, ContextInterface $context);
}

