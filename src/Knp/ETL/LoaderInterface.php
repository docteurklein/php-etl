<?php

namespace Knp\ETL;

/**
 * @author     Florian Klein <florian.klein@free.fr>
 */
interface LoaderInterface
{
    /**
     * loads data into some other persistence service
     *
     * @param mixed $data the data to load
     * @param ContextInterface $context the shared context for current iteration / row / whatever
     *
     * @return mixed
     */
    function load($data, ContextInterface $context);

    /**
     * Flush the loader
     *
     * @param ContextInterface $context the shared context for current iteration / row / whatever
     **/
    function flush(ContextInterface $context);

    /**
     * Reset the loader
     *
     * @param ContextInterface $context the shared context for current iteration / row / whatever
     **/
    function clear(ContextInterface $context);
}

