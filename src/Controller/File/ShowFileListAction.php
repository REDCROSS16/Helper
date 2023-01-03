<?php declare(strict_types=1);

namespace App\Controller\File;

use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowFileListAction extends AbstractController
{
    #[Route('/file/file_list', name: 'file_list')]
    public function __invoke(DocumentRepository $documentRepository): Response
    {
        $files = $documentRepository->findAll();

        return $this->render(
            'file_list/file_list.html.twig',
            [
                'files' => $files,
            ]
        );
    }
}
