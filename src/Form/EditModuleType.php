<?php

namespace App\Form;

use App\Domain\LearningModuleType;
use App\Entity\LearningModule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('badge')
            ->add('type', ChoiceType::class, [
                'label' => 'Select type:',
                'choices' => [
                    (string)LearningModuleType::soft(),
                    (string)LearningModuleType::hard()
                ],
                'choice_label' => static function($value){ return $value; },
                'multiple'=>false,
                'expanded'=>true
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LearningModule::class,
        ]);
    }
}
