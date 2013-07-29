<?php

namespace Knp\ETL;

/**
 * @author     Florian Klein <florian.klein@free.fr>
 */
interface ExtractorInterface
{
    /**
     * extract data to be transformed
     *
     * @return array
     */
    function extract(ContextInterface $context);
}

