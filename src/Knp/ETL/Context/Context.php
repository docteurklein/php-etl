<?php

namespace Knp\ETL\Context;

use Knp\ETL\ContextInterface;

/**
 * @author     Florian Klein <florian.klein@free.fr>
 */
class Context implements ContextInterface
{
    private $extractedData;
    private $transformedData;
    private $identifier;

    public function __construct($id = null)
    {
        $this->identifier = $id;
        $this->extractedData = [];
        $this->transformedData = [];
    }

    /**
     * @return array the shared context between extractors, loader and persisters
     **/
    public function getExtractedData()
    {
        return $this->extractedData;
    }

    /**
     * @param mixed the extracted data
     **/
    public function setExtractedData($data)
    {
        $this->extractedData = $data;
    }

    /**
     * @return mixed the transformed data
     **/
    public function getTransformedData()
    {
        return $this->transformedData;
    }

    /**
     * @param mixed the transformed data
     **/
    public function setTransformedData($data)
    {
        $this->transformedData = $data;
    }

    /**
     * @return mixed the identifier value of current data
     **/
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param mixed the identifier value of current data
     **/
    public function setIdentifier($id)
    {
        $this->identifier = $id;
    }
}
