<?php

namespace App\Form;

use App\Entity\Language;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageSwitcherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('language', EntityType::class, [ // build a select drop-down containing all of the entities in that Class
            'class' => Language::class,             // that class is the Language entities

            'choice_label' => 'name', // property that should be used for displaying the entities
            'choice_value' => 'code'  // give the langcode as a value
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
