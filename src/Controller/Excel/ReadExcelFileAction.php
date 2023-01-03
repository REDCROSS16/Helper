<?php declare(strict_types=1);

namespace App\Controller\Excel;

use Box\Spout\Common\Entity\Row;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReadExcelFileAction extends AbstractController
{
    public function __invoke(): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $file  = 'path';
        $spout = ReaderEntityFactory::createReaderFromFile($file);
        $spout->open($file);

        foreach ($spout->getSheetIterator() as $sheet) {
            foreach ($sheet as $row) {
                /** @var Row $row */
                $cells = $row->getCells();
                dd($cells);
            }
        }

        $spout->close();

        return $this->json('reader is closed');
    }
}
