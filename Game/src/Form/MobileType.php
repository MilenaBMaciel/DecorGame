<?php

namespace App\Form;

use App\Entity\Mobile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class MobileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $constraints = [new Image(maxSize: '2M')];

        // Ao criar, a imagem é obrigatória; ao editar, só se quiser trocar
        if ($options['image_required']) {
            $constraints[] = new NotNull(message: 'Escolha uma imagem para o móvel.');
        }

        $builder
            ->add('name')
            ->add('imageFile', FileType::class, [
                'label' => 'Image (PNG, JPG, GIF ou WEBP, até 2 MB)',
                'mapped' => false,
                'required' => $options['image_required'],
                'constraints' => $constraints,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mobile::class,
            'image_required' => true,
        ]);
        $resolver->setAllowedTypes('image_required', 'bool');
    }
}
