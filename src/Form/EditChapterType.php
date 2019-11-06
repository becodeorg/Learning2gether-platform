<?php

namespace App\Form;

use App\Entity\Chapter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditChapterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('edit', SubmitType::class)
        ;
        $builder
            ->add('translations', CollectionType::class, [
                'entry_type' => EditChapterTranslationsType::class,
                'entry_options' => ['label' => false],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Chapter::class,
        ]);
    }
}
