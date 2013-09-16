<?php

namespace Knp\ETL\Extractor;

use Knp\ETL\ExtractorInterface;
use Knp\ETL\ContextInterface;

use Psr\Log\LoggerAwareTrait;

/**
 * @author Timothée Barray <tim@amicalement-web.net>
 */
class JsonExtractor implements ExtractorInterface, \Iterator, \Countable
{
    use LoggerAwareTrait;

    private $json;
    private $identifierColumn;
    private $resource;
    private $adapter;
    private $path;

    /**
     * Regarding the following json structure :
     * {
     *  items: [{
     *      'name': 'Riri'
     *  }, {
     *      'name': 'Fifi'
     *  }, {
     *      'name': 'Loulou'
     *  }] 
     * }
     * You can extract only all names with $path value : "items.*.name"
     *
     * @param string $resource Filename or URL for the json file
     * @param string $path The path in json file to go to your target nodes. Example : "nodes.*.node"
     * @param Closure $adapter A closure which wraps the way to get the content of json file
     */
    public function __construct($resource, $path = null, \Closure $adapter = null)
    {
        if (null !== $this->logger) {
            $this->logger->debug('extracting from: '.$resource);
        }

        $this->resource = $resource;
        $this->adapter = $adapter;

        if (is_string($path)) {
            $this->path = explode('.', $path);
        }
    }

    public function extract(ContextInterface $context)
    {
        $current = $this->current();
        $this->next();

        return $current;
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

    public function count()
    {
        return $this->getIterator()->count();
    }

    public function seek($position)
    {
        return $this->getIterator()->seek($position);
    }

    private function getIterator()
    {
        if (null === $this->json) {
            $json = json_decode($this->getContent());

            if (null === $json) {
                throw new \RuntimeException(sprintf('%s could not be parsed as json file', $this->resource));
            }

            if (is_array($this->path)) {
                $json = $this->parseJson(new \RecursiveArrayIterator($json), array(), 0);
            }

            // If we gets only an object, put it in array to avoid iterate on its properties
            if (!is_array($json)) {
                $json = array($json);
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

    private function parseJson(\RecursiveArrayIterator $json, array $data, $level)
    {
        foreach ($json as $k => $j) {
            if (!isset($this->path[$level])) {
                return $data;
            }

            if ($k !== $this->path[$level] && $this->path[$level] !== '*') {
                continue;
            }

            if ($k === end($this->path)) {
                if (is_array($j)) {
                    $data = array_merge($data, $j);
                } else {
                    $data[] = $j;
                }
            }

            if ($json->hasChildren()) {
                $data = $this->parseJson($json->getChildren(), $data, $level + 1);
            }
        }

        return $data;
    }
}