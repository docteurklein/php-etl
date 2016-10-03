<?php
namespace Knp\ETL\Loader;

use Knp\ETL\ContextInterface;
use Knp\ETL\LoaderInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SplFileObject;

class FileLoader implements LoaderInterface
{
    protected $file;
    protected $logger;

    public function __construct(SplFileObject $file, LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
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