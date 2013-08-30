<?php
namespace Knp\ETL\Loader;

use Psr\Log\LoggerAwareTrait;
use Knp\ETL\ContextInterface;
use Knp\ETL\LoaderInterface;
use SplFileObject;

class FileLoader implements LoaderInterface
{
    use LoggerAwareTrait;

    protected $file;

    public function __construct(SplFileObject $file)
    {
        $this->file = $file;
    }

    public function load($data, ContextInterface $context)
    {
        $r = $this->file->fwrite($data);

        if (null !== $this->logger) {
            $this->logger->debug(sprintf('Wrote %s bytes in %s', $r, $this->file->getBasename()));
        }

        return $r;
    }

    public function flush(ContextInterface $context)
    {
    }

    public function clear(ContextInterface $context)
    {
    }
}