<?php

namespace App\Form;

use App\Entity\Quiz;
use App\Entity\QuizQuestion;
use App\Entity\QuizQuestionTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

//TODO No longer used, but for the moment we keep it

class QuizQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('questionNumber')
        ;
        $builder
            ->add('translations', CollectionType::class, [
                'entry_type' => QuizQuestionTranslationType::class,
                'entry_options' => ['label' => false],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuizQuestion::class,
        ]);
    }
}
