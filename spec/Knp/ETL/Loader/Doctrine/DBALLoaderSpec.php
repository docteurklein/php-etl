<?php

namespace spec\Knp\ETL\Loader\Doctrine;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Doctrine\DBAL\Connection;
use Knp\ETL\Context\Doctrine\DBALContext;

class DBALLoaderSpec extends ObjectBehavior
{
    function let(Connection $conn)
    {
        $this->beConstructedWith($conn);
    }

    function it_should_load_existing_data()
    {
        $data = [];
        $this->load($data, new DBALContext);
    }
}
