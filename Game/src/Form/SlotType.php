<?php

namespace App\Form;

use App\Entity\Mobile;
use App\Entity\Slot;
use App\Repository\MobileRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SlotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mobile', EntityType::class, [
                'class' => Mobile::class,
                'choice_label' => 'name',
                'query_builder' => fn (MobileRepository $repository) => $repository->createQueryBuilder('m')->orderBy('m.name', 'ASC'),
                // Sem móvel escolhido = slot vazio (mobile fica null)
                'required' => false,
                'placeholder' => 'Empty',
                // Cada móvel vira uma opção com a imagem (o template desenha as miniaturas)
                'expanded' => true,
                'choice_attr' => fn (Mobile $mobile) => ['data-image' => $mobile->getImage()],
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
