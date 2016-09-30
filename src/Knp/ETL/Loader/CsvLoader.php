<?php

namespace Knp\ETL\Loader;

use Knp\ETL\ContextInterface;

class CsvLoader extends FileLoader
{
    public function load($data, ContextInterface $context)
    {
        $r = $this->file->fputcsv($data);
        $this->logger->debug('Write a field array as a CSV line', ['data' => $data, 'filename' => $this->file->getBasename(), 'bytes' => $r]);

        return $r;
    }
}
