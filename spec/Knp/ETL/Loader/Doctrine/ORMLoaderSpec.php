<?php

namespace spec\Knp\ETL\Loader\Doctrine;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\ETL\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;

class ORMLoaderSpec extends ObjectBehavior
{
    function let(ManagerRegistry $doctrine, ObjectManager $manager)
    {
        $doctrine->getManager()->willReturn($manager);
        $this->beConstructedWith($doctrine);
    }

    function it_should_load_existing_data(\stdClass $entity)
    {
        $this->load($entity, new Context);
    }
}
