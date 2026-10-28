<?php

namespace App\Form;

use App\Entity\Furniture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class FurnitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $constraints = [new Image(maxSize: '2M')];

        // Ao criar, a imagem é obrigatória; ao editar, só se quiser trocar
        if ($options['image_required']) {
            $constraints[] = new NotNull(message: 'Please choose an image for the furniture.');
        }

        $builder
            ->add('name')
            ->add('imageFile', FileType::class, [
                'label' => 'Image (PNG, JPG, GIF or WEBP, up to 2 MB)',
                'mapped' => false,
                'required' => $options['image_required'],
                'constraints' => $constraints,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Furniture::class,
            'image_required' => true,
        ]);
        $resolver->setAllowedTypes('image_required', 'bool');
    }
}
