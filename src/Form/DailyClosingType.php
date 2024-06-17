<?php

namespace App\Form;

use App\Entity\DailyClosing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DailyClosingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('closingDate', DateType::class, [
                'label' => false,
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'min' => date('Y-m-d')
                ]
            ])
            ->add('morningOpening', null, [
                'label' => false,
                'required' => false,
            ])
            ->add('morningClosing', null, [
                'label' => false,
                'required' => false,
            ])
            ->add('eveningOpening', null, [
                'label' => false,
                'required' => false,
            ])
            ->add('eveningClosing', null, [
                'label' => false,
                'required' => false,
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
            'data_class' => DailyClosing::class,
        ]);
    }
}
