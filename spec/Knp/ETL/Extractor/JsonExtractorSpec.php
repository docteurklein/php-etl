<?php

namespace spec\Knp\ETL\Extractor;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\ETL\Context\Context;

class JsonExtractorSpec extends ObjectBehavior
{
    function it_should_iterate_over_a_json_file()
    {
        $this->beConstructedWith(__DIR__.'/Fixture/TransformableEntity.json');

        $entity = $this->extract(new Context);
        $entity->id->shouldBe(123);
        $entity->name->shouldBe('John');
        $entity->surname->shouldBe('Smith');

        $entity = $this->extract(new Context);
        $entity->id->shouldBe(145);
        $entity->name->shouldBe('Chuck');
        $entity->surname->shouldBe('Norris');
    }

    function it_should_throw_an_exception_if_not_json()
    {
        $this->beConstructedWith(__DIR__.'/Fixture/TransformableEntity.csv');

        $this->shouldThrow('\RuntimeException')->duringExtract(new Context);
    }

    function it_should_count_lines()
    {
        $this->beConstructedWith(__DIR__.'/Fixture/TransformableEntity.json');

        $this->count()->shouldBe(3);
    }

    function it_should_move_to_given_position()
    {
        $this->beConstructedWith(__DIR__.'/Fixture/TransformableEntity.json');

        $this->seek(1);
        $entity = $this->current();
        $entity->id->shouldBe(145);
        $entity->name->shouldBe('Chuck');
        $entity->surname->shouldBe('Norris');

        $this->seek(0);
        $entity = $this->current();
        $entity->id->shouldBe(123);
        $entity->name->shouldBe('John');
        $entity->surname->shouldBe('Smith');
    }

    function it_should_returns_only_name()
    {
        $this->beConstructedWith(__DIR__.'/Fixture/TransformableEntity.json', '*.name');

        $e = $this->extract(new Context);
        $e->shouldBe('John');

        $e = $this->extract(new Context);
        $e->shouldBe('Chuck');
    }
}
