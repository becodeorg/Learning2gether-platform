<?php

namespace App\Form;

use App\Entity\LearningModule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('image', FileType::class, [
                'label' => 'upload image ',
                'mapped' => 'false'
            ])
            ->add('badge', null , [
                'label' => 'badgr.io badge hash ',
            ])
            ->add('create', SubmitType::class)
        ;

        $builder->add('translations', CollectionType::class, [
            'entry_type' => CreateModuleTranslationType::class,
            'entry_options' => ['label' => false],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LearningModule::class,
        ]);
    }
}
