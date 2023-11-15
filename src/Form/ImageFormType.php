<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        // W przypadku barku właściwości w Entity, musimy zaznaczyć to używają 'mapped' => false
        // Constraints -> walidacja

        $builder
            ->add('imageFile', FileType::class, [
                'mapped'      => false,
                'constraints' => [
                    new File([
                        'maxSize'          => '2048k',
                        'mimeTypes'        => ['image/gif', 'image/jpg', 'image/png', 'image/svg+xml'],
                        'mimeTypesMessage' => 'Please upload a valid image file: png,jpg,gif,svg.'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
