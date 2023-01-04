<?php

namespace App\Service;

use App\Exception\BigFileException;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\XLSX\Sheet;
use Symfony\Component\HttpFoundation\File\File;

class ExcelReader
{
    public const MAX_FILE_SIZE = 30000000;
    public function __construct(
      private readonly File $file
    )
    {
    }

    public function readFile()
    {
        $size = $this->file->getSize();

        if ($size > self::MAX_FILE_SIZE) {
            throw new BigFileException('file is too big');
        }

        $spout = ReaderEntityFactory::createReaderFromFile($this->file->getPath());
        $spout->open($this->file->getPath());

        // перебираем страницы нашего файла
        foreach ($spout->getSheetIterator() as $sheet) {
            /** @var Sheet $sheet */
            foreach ($sheet->getRowIterator() as $row) {
                /** @var Row $row */
                $cells = $row->getCells();
                \print_r($cells);
            }
        }

        $spout->close();
    }
}