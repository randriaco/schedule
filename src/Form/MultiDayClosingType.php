<?php

namespace App\Form;

use App\Entity\MultiDayClosing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultiDayClosingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('beginClosing', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'attr' => [
                    'min' => date('Y-m-d')
                ]
            ])
            ->add('finisgClosing', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'attr' => [
                    'min' => date('Y-m-d')
                ]
            ])
            ->add('pattern', TextType::class, [
                'label' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MultiDayClosing::class,
        ]);
    }
}
