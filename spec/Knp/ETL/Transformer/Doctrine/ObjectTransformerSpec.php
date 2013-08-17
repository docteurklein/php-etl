<?php

namespace spec\Knp\ETL\Transformer\Doctrine;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\ETL\Transformer\DataMap;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\ETL\Context\Doctrine\ORMContext;
use Doctrine\Common\Persistence\ObjectRepository;

require_once __DIR__.'/Fixture/TransformableEntity.php';

class ObjectTransformerSpec extends ObjectBehavior
{
    const className = 'spec\Knp\ETL\Transformer\Doctrine\Fixture\TransformableEntity';

    function let(ManagerRegistry $doctrine, ObjectRepository $repository)
    {
        $doctrine->getRepository(self::className)->willReturn($repository);

        $map = new DataMap([
            0 => 'id',
            1 => 'name',
            2 => 'surname',
        ]);

        $this->beConstructedWith(self::className, $map, $doctrine);
    }

    function it_should_transform_an_array_to_an_entity()
    {
        $entity = $this->transform(['id', 'name', 'surname'], new ORMContext('id'));

        $entity->shouldHaveType(self::className);
        $entity->id->shouldBe('id');
        $entity->name->shouldBe('name');
        $entity->surname->shouldBe('surname');
    }

    // @TODO move this to DataMap test
    function it_should_fail_if_column_count_does_not_match()
    {
        $this->shouldThrow(new \LogicException('input does not contain expected size 3, 4 given'))
            ->duringTransform(['id', 'name', 'surname', 'test'], new ORMContext('id'))
        ;
    }
}
