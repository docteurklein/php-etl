<?php

namespace spec\Knp\ETL\Transformer;

use PhpSpec\ObjectBehavior;
use Knp\ETL\Context\Context;

require_once __DIR__.'/Doctrine/Fixture/TransformableEntity.php';

class DataMapSpec extends ObjectBehavior
{
    const className = 'spec\Knp\ETL\Transformer\Doctrine\Fixture\TransformableEntity';

    function let()
    {
        $map = [
            0 => 'id',
            1 => 'name',
            2 => 'surname',
        ];

        $this->beConstructedWith($map);
    }

    function it_should_transform_an_array_to_an_array()
    {
        $data = $this->transform([123, 'John', 'Smith'], new Context('id'));

        $data->shouldBeArray();        
        $data->shouldBe(['id' => 123, 'name' => 'John', 'surname' => 'Smith']);
    }

    function it_should_transform_an_array_to_an_entity()
    {
        $context = new Context('id');
        $class = self::className;
        $context->setTransformedData(new $class());
        $entity = $this->transform([123, 'John', 'Smith'], $context);

        $entity->shouldHaveType(self::className);
        $entity->id->shouldBe(123);
        $entity->name->shouldBe('John');
        $entity->surname->shouldBe('Smith');
    }
}
