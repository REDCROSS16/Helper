<?php declare(strict_types=1);

namespace App\Controller\Excel;

use App\Service\ExcelReader;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\XLSX\Sheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;

class ReadExcelFileAction extends AbstractController
{
    #[Route('/excel/read', name: 'excel_file_reader')]
    public function __invoke(string $dir): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $path  = $dir . DIRECTORY_SEPARATOR . 'WY_1.xlsx';
        $file  = new File($path);

        $excelReader = new ExcelReader($file);
        $excelReader->readFile();



        return $this->json('reader is closed');
    }
}
