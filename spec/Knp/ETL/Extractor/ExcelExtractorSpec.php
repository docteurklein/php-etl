<?php

namespace spec\Knp\ETL\Extractor;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Knp\ETL\Context\Context;

class ExcelExtractorSpec extends ObjectBehavior
{
    function it_should_iterate_over_a_xls_file()
    {
        $this->beConstructedWith(__DIR__.'/Fixture/TransformableEntity.xls');

        $this->extract(new Context)->shouldBe([(double) 1, 'a', 'b']);
        $this->extract(new Context)->shouldBe([(double) 2, 'c', 'd']);
    }

    function it_should_count_lines()
    {
        $this->beConstructedWith(__DIR__.'/Fixture/TransformableEntity.csv');

        $this->count()->shouldBe(7);
    }
}
