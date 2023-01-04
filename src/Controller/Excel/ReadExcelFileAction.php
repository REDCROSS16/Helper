<?php declare(strict_types=1);

namespace App\Controller\Excel;

use Box\Spout\Common\Entity\Row;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\XLSX\Sheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;

class ReadExcelFileAction extends AbstractController
{
    public const MAX_FILE_SIZE = 30000000;

    #[Route('/excel/read', name: 'excel_file_reader')]
    public function __invoke(string $dir): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $path  = $dir . DIRECTORY_SEPARATOR . 'WY_1.xlsx';
        $file  = new File($path);

        $size = $file->getSize();

        if ($size > self::MAX_FILE_SIZE) {
            return $this->json('File is too big, filesize: ' . $size);
        }

        $spout = ReaderEntityFactory::createReaderFromFile($path);
        $spout->open($file);

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

        return $this->json('reader is closed');
    }
}
