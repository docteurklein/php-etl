<?php

namespace spec\Knp\ETL\Transformer\Doctrine\Fixture;

class TransformableEntity
{
    public $id;
    public $name;
    public $surname;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setSurname($surname)
    {
        $this->surname = $surname;
    }
}
