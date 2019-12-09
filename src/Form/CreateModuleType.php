<?php

namespace App\Form;

use App\Domain\LearningModuleType;
use App\Entity\LearningModule;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormTypeInterface;

class CreateModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('badge', TextType::class , [
                'label' => 'badgr.io badge hash ',
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Select type:',
                'choices' => [LearningModuleType::soft(), LearningModuleType::hard()],
                'choice_label' => static function($value){ return $value; },
                'multiple'=>false,
                'expanded'=>true
            ])
            ->add('create', SubmitType::class, [
                'label' => 'Create chapter'
            ])
        ;

        $builder->add('translations', CollectionType::class, [
            'entry_type' => CreateModuleTranslationType::class,
            'entry_options' => ['label' => false],
            'label' => 'Translations',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LearningModule::class,
        ]);
    }
}
