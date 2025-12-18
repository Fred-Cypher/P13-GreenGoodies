<?php

namespace App\Form;

use App\Entity\OrderDetail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderDetailFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité à commander',
                'attr' => ['min' => 0],
                'data' => $options['quantity'] ?? 0
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'quantity' => null,
        ]);
    }
}
