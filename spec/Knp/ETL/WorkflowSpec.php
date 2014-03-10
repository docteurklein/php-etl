<?php

namespace spec\Knp\ETL;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Knp\ETL\Context\Context;
use Knp\ETL\ContextInterface;
use Knp\ETL\ExtractorInterface;
use Knp\ETL\TransformerInterface;
use Knp\ETL\LoaderInterface;

/**
 * @author Daniel Boetsch <dan.boetsch@gmail.com>
 */
class ExtractorMock implements ExtractorInterface
{
    protected $val = true;

    public function extract(ContextInterface $context)
    {
      if ($this->val)
      {
        $this->val = false;

        return true;
      }

      return null;
    }
}

class WorkflowSpec extends ObjectBehavior
{
    function let(EventDispatcher $dispatcher){
        $this->beConstructedWith($dispatcher);
        $this->getDispatcher()->shouldReturn($dispatcher);
    }

    function it_should_dispatch_event(
        EventDispatcherInterface $dispatcher,
        TransformerInterface $transformer, LoaderInterface $loader
    )
    {
        $c = new \Pimple([
            'transformer' => $transformer,
            'loader' => $loader,
            'context' => new Context(),
        ]);

        $c['etl'] = new \Pimple([
            'parent' => $c,
            'e' => function() {
                return new ExtractorMock();
            },
            't' => function($c) {
                return $c['parent']['transformer'];
            },
            'l' => function($c) {
                return $c['parent']['loader'];
            },
            'c' => function($c) {
                return $c['parent']['context'];
            },
        ]);

        $dispatcher->dispatch('workflow.post_extract', Argument::type('Symfony\Component\EventDispatcher\GenericEvent'))->shouldBeCalled();
        $dispatcher->dispatch('workflow.post_transform', Argument::type('Symfony\Component\EventDispatcher\GenericEvent'))->shouldBeCalled();
        $dispatcher->dispatch('workflow.post_load', Argument::type('Symfony\Component\EventDispatcher\GenericEvent'))->shouldBeCalled();
        $dispatcher->dispatch('workflow.post_flush', Argument::type('Symfony\Component\EventDispatcher\GenericEvent'))->shouldBeCalled();

        $this->process($c);
    }

    function it_should_flush_the_workflow(
        TransformerInterface $transformer, LoaderInterface $loader
    )
    {
        $c = new \Pimple([
            'transformer' => $transformer,
            'loader' => $loader,
            'context' => new Context(),
        ]);

        $c['etl'] = new \Pimple([
            'parent' => $c,
            'e' => function() {
                return new ExtractorMock();
            },
            't' => function($c) {
                return $c['parent']['transformer'];
            },
            'l' => function($c) {
                return $c['parent']['loader'];
            },
            'c' => function($c) {
                return $c['parent']['context'];
            },
        ]);

        $this->process($c);
        $this->flush();
    }
}
