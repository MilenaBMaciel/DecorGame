<?php

namespace App\Form;

use App\Entity\Furniture;
use App\Entity\Slot;
use App\Repository\FurnitureRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SlotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('furniture', EntityType::class, [
                'class' => Furniture::class,
                'choice_label' => 'name',
                'query_builder' => fn (FurnitureRepository $repository) => $repository->createQueryBuilder('m')->orderBy('m.name', 'ASC'),
                // Sem móvel escolhido = slot vazio (furniture fica null)
                'required' => false,
                'placeholder' => 'Empty',
                // Cada móvel vira uma opção com a imagem (o template desenha as miniaturas)
                'expanded' => true,
                'choice_attr' => fn (Furniture $furniture) => ['data-image' => $furniture->getImage()],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Slot::class,
        ]);
    }
}
