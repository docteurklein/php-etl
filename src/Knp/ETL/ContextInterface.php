<?php

namespace Knp\ETL;

/**
 * @author     Florian Klein <florian.klein@free.fr>
 */
interface ContextInterface
{
    /**
     * @return array the shared context between extractors, loader and persisters
     **/
    public function getExtractedData();

    /**
     * @param mixed the extracted data
     **/
    public function setExtractedData($data);

    /**
     * @return mixed the transformed data
     **/
    public function getTransformedData();

    /**
     * @param mixed the transformed data
     **/
    public function setTransformedData($data);

    /**
     * @return mixed the identifier value of current data
     **/
    public function getIdentifier();

    /**
     * @param mixed the identifier value of current data
     **/
    public function setIdentifier($id);
}
