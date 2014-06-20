<?php

namespace Knp\ETL;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Daniel Boetsch <dan.boetsch@gmail.com>
 */
class Workflow
{
    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var ExtractorInterface
     */
    protected $extractor;

    /**
     * @var TransformerInterface
     */
    protected $transformer;

    /**
     * @var LoaderInterface
     */
    protected $loader;

    /**
     * @var ContextInterface
     */
    protected $context;

    /**
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     */
    public function __construct(\Symfony\Component\EventDispatcher\EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     *
     * Execute the process extract transform and load
     *
     * @param \Pimple $flow
     */
    public function process(\Pimple $flow)
    {
        $this->extractor = $flow['etl']['e'];
        $this->transformer = $flow['etl']['t'];
        $this->loader = $flow['etl']['l'];
        $this->context = $flow['etl']['c'];

        while (null !== $input = $this->extractor->extract($this->context))
        {
            try
            {
                $this->dispatcher->dispatch('workflow.post_extract', new GenericEvent($this->extractor,array('input'=>$input)));

                $output = $this->transformer->transform($input, $this->context);
                $this->dispatcher->dispatch('workflow.post_transform', new GenericEvent($this->transformer,array('output'=>$output)));

                $this->loader->load($output, $this->context);
                $this->dispatcher->dispatch('workflow.post_load', new GenericEvent($this->loader,array('output'=>$output)));
            }
            catch (\LogicException $e)
            {
            }
        }

        $this->loader->flush($this->context);
        $this->dispatcher->dispatch('workflow.post_flush', new GenericEvent($this->loader));
    }

    /*
     *
     * @return Event Dispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /*
     * @return ContextInterface
     */
    public function getContext()
    {
        return $this->context;
    }

    /*
     * @return LoaderInterface
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /*
     * @return TransformerInterface
     */
    public function getTransformer()
    {
        return $this->transformer;
    }

    /*
     * @return void
     */
    public function flush()
    {
        $this->loader->flush($this->context);
    }
}
