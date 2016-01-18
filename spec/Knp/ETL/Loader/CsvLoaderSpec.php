<?php

namespace spec\Knp\ETL\Loader;

use PhpSpec\ObjectBehavior;
use Knp\ETL\Context\Context;

class CsvLoaderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new \SplFileObject(sys_get_temp_dir().'/etl-test.csv', 'w+'));
    }

    function it_should_load_existing_data(\stdClass $entity)
    {
        $this->load(['Cell1', 'Cell2'], new Context)->shouldBe(12);
    }

    function letgo()
    {
        unlink(sys_get_temp_dir().'/etl-test.csv');
    }
}
