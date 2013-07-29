<?php

namespace Knp\ETL\Context\Doctrine;

use Knp\ETL\Context\Context as BaseContext;

/**
 * @author     Florian Klein <florian.klein@free.fr>
 */
class ORMContext extends BaseContext
{
    private $shouldFlush = false;
    private $shouldClear = false;

    public function shouldFlush($should = null)
    {
        if (null === $should) {
            return $this->shouldFlush;
        }

        $this->shouldFlush = $should;
    }

    public function shouldClear($should = null)
    {
        if (null === $should) {
            return $this->shouldClear;
        }

        $this->shouldClear = $should;
    }
}
