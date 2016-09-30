<?php
namespace Knp\ETL\Loader;

use Psr\Log\LoggerAwareTrait;
use Knp\ETL\ContextInterface;
use Knp\ETL\LoaderInterface;
use Psr\Log\NullLogger;
use SplFileObject;

class FileLoader implements LoaderInterface
{
    use LoggerAwareTrait;

    protected $file;

    public function __construct(SplFileObject $file)
    {
        $this->logger = new NullLogger();
        $this->file = $file;
    }

    public function load($data, ContextInterface $context)
    {
        $r = $this->file->fwrite($data);
        $this->logger->debug('Write to file', ['data' => $data, 'filename' => $this->file->getBasename(), 'bytes' => $r]);

        return $r;
    }

    public function flush(ContextInterface $context)
    {
    }

    public function clear(ContextInterface $context)
    {
    }
}