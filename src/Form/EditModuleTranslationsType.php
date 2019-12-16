<?php

namespace App\Form;

use App\Entity\LearningModuleTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditModuleTranslationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'attr' => ['placeholder' => 'Title'],
                'label' => false,
                'required' => false,
                'empty_data' => '',
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['placeholder' => 'Description', 'rows' => 4],
                'label' => false,
                'required' => false,
                'empty_data' => '',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LearningModuleTranslation::class,
        ]);
    }
}
