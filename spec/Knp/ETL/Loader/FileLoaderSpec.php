<?php

namespace spec\Knp\ETL\Loader;

use PhpSpec\ObjectBehavior;
use Knp\ETL\Context\Context;

class FileLoaderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new \SplFileObject(sys_get_temp_dir().'/etl-test', 'w+'));
    }

    function it_should_load_existing_data(\stdClass $entity)
    {
        $this->load('hey you!', new Context)->shouldBe(8);
    }

    function letgo()
    {
        unlink(sys_get_temp_dir().'/etl-test');
    }
}