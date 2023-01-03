<?php declare(strict_types=1);

namespace App\Controller\File;

use App\Entity\Document;
use App\Form\DocumentsUploadType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploadAction extends AbstractController
{
    public function __construct(readonly private EntityManagerInterface $em)
    {
    }

    #[Route('/file/uploader', name: 'app_file_uploader')]
    public function __invoke(Request $request, SluggerInterface $slugger): Response
    {
        $document = new Document();
        $form     = $this->createForm(DocumentsUploadType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $documentFile */
            $documentFile = $form->get('filename')->getData();

            if ($documentFile) {
                $originalFilename = \pathinfo($documentFile->getClientOriginalExtension(), PATHINFO_FILENAME);
                $safeFileName     = $slugger->slug($originalFilename);
                $newFileName      = $safeFileName . '-' . \uniqid() . '.' . $documentFile->guessExtension();

                try {
                    $documentFile->move(
                        $this->getParameter('files_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {
                }

                $document->setFilename($newFileName);
                $document->setDate(new \DateTime());
                $document->setPath($originalFilename);
                $this->em->persist($document);
                $this->em->flush();

                return $this->redirect('file_list');
            }
        }

        return $this->render('file_uploader/index.html.twig', [
            'form' => $form,
        ]);
    }
}
