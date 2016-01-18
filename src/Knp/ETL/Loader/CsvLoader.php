<?php

namespace Knp\ETL\Loader;

use Knp\ETL\ContextInterface;

class CsvLoader extends FileLoader
{
    public function load($data, ContextInterface $context)
    {
        $r = $this->file->fputcsv($data);

        if (null !== $this->logger) {
            $this->logger->debug(sprintf('Wrote %s bytes in %s', $r, $this->file->getBasename()));
        }

        return $r;
    }
}
