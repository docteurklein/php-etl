<?php

namespace spec\Knp\ETL\Transformer;

use PhpSpec\ObjectBehavior;
use Knp\ETL\Context\Context;

require_once __DIR__.'/Doctrine/Fixture/TransformableEntity.php';

class CallbackTransformerSpec extends ObjectBehavior
{
    function let()
    {
        $callback = function ($data, $target) {
            foreach ($data as $d) {
                $target[] = gettype($d);
            }

            return $target;
        };

        $this->beConstructedWith($callback);
    }

    function it_should_transform_an_array_to_an_array()
    {
        $data = $this->transform([123, 'John', false], new Context('id'));

        $data->shouldBeArray();        
        $data->shouldBe(['integer', 'string', 'boolean']);
    }
}
