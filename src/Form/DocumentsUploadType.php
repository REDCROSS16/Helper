<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class DocumentsUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filename', FileType::class, [
                'label'       => 'Document',
                'mapped'      => false,
                'constraints' => [
                    new File([
                        'maxSize'          => '1024k',
//                        'mimeTypes' => [
//                            'application/pdf',
//                            'application/x-pdf',
//                        ],
                        'mimeTypesMessage' => 'Please upload a valid xls,csv document',
                    ]),
            ],
            ])
            ->add('submit', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
