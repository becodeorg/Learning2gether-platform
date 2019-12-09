<?php


namespace App\Form;


use App\Entity\QuizAnswer;
use App\Entity\QuizAnswerTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\QuizAnswerType;


class QuizAnswerTranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quizAnswer', QuizAnswerType::class)
        ;
        $builder
            ->add('title', null, [
                'attr' => ['placeholder' => 'Answer'],
                'label' => false,
                'required' => false,
                'empty_data' => '',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuizAnswerTranslation::class,
        ]);
    }
}