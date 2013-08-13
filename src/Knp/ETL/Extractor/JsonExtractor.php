<?php

namespace Knp\ETL\Extractor;

use Knp\ETL\ExtractorInterface;
use Knp\ETL\ContextInterface;

use Psr\Log\LoggerAwareTrait;

/**
 * @author Timothée Barray <tim@amicalement-web.net>
 */
class JsonExtractor implements ExtractorInterface, \Iterator
{
    use LoggerAwareTrait;

    private $json;
    private $identifierColumn;
    private $resource;
    private $adapter;

    /**
     * @param string $resource Filename or URL for the json file
     * @param string $startNode The node in json file you want to pass through
     * @param Closure $adapter A closure which wraps the way to get the content of json file
     */
    public function __construct($resource, $startNode = null, \Closure $adapter = null)
    {
        if (null !== $this->logger) {
            $this->logger->debug('extracting from: '.$resource);
        }

        $this->resource = $resource;
        $this->adapter = $adapter;
        $this->startNode = $startNode;
    }

    public function extract(ContextInterface $context)
    {
        return $this->current();
    }

    public function rewind()
    {
        return $this->getIterator()->rewind();
    }

    public function current()
    {
        return $this->getIterator()->current();
    }

    public function key()
    {
        return $this->getIterator()->key();
    }

    public function next()
    {
        $next = $this->getIterator()->next();
        if (null !== $this->logger) {
            $this->logger->debug(sprintf('%s:%d', $this->resource, $this->key()));
        }

        return $next;
    }

    public function valid()
    {
        return $this->getIterator()->valid();
    }

    private function getIterator()
    {
        if (null === $this->json) {
            $json = json_decode($this->getContent());

            if ($this->startNode) {
                $json = $json->{$this->startNode};
            }
            
            $this->json = new \ArrayIterator($json);
        }

        return $this->json;
    }

    private function getContent()
    {
        if (null === $this->adapter) {
            return file_get_contents($this->resource);
        }

        $adapter = $this->adapter;

        return $adapter($this->resource);
    }
}