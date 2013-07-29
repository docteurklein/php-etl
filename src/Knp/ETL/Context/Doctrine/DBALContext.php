<?php

namespace Knp\ETL\Context\Doctrine;

use Knp\ETL\Context\Context as BaseContext;

/**
 * @author     Florian Klein <florian.klein@free.fr>
 */
class DBALContext extends BaseContext
{
    public function __construct($id = null, $tableName = null)
    {
        parent::__construct($id);
        $this->tableName = $tableName;
    }

    private $tableName;

    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param mixed the extracted data
     **/
    public function setTableName($name)
    {
        $this->tableName = $name;
    }
}
