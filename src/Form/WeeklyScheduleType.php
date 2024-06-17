<?php

namespace App\Form;

use App\Entity\WeeklySchedule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeeklyScheduleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var WeeklySchedule $weeklySchedule */
        foreach ($options['data']['weekly_schedule'] as $weeklySchedule) {
            $builder
                ->add('morningOpening'. $weeklySchedule->getId(), null, [
                    'label' => false,
                    'required' => false,
                    'data' => $weeklySchedule->getMorningOpening()
                ])
                ->add('morningClosing'. $weeklySchedule->getId(), null, [
                    'label' => false,
                    'required' => false,
                    'data' => $weeklySchedule->getMorningClosing()
                ])
                ->add('eveningOpening'. $weeklySchedule->getId(), null, [
                    'label' => false,
                    'required' => false,
                    'data' => $weeklySchedule->getEveningOpening()
                ])
                ->add('eveningClosing'. $weeklySchedule->getId(), null, [
                    'label' => false,
                    'required' => false,
                    'data' => $weeklySchedule->getEveningClosing()
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
