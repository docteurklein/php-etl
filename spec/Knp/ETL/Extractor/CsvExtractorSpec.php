<?php

namespace spec\Knp\ETL\Extractor;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\ETL\Context\Context;

class CsvExtractorSpec extends ObjectBehavior
{
    function it_should_iterate_over_a_csv_file()
    {
        $this->beConstructedWith(__DIR__.'/Fixture/TransformableEntity.csv');

        $this->extract(new Context)->shouldBe(['1', 'a', 'b']);
        $this->extract(new Context)->shouldBe(['2', 'c', 'd']);
    }

    function it_should_count_lines()
    {
        $this->beConstructedWith(__DIR__.'/Fixture/TransformableEntity.csv');

        $this->count()->shouldBe(8);
    }
}
