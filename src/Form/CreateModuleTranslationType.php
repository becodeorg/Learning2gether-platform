<?php

namespace App\Form;

use App\Entity\LearningModuleTranslation;
use Doctrine\Bundle\DoctrineBundle\Dbal\Logging\BacktraceLogger;
use Doctrine\DBAL\Types\TextType;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class CreateModuleTranslationType extends AbstractType
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
                'attr' => ['placeholder' => 'Description'],
                'label' => false,
                'required' => false,
                'empty_data' => '',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LearningModuleTranslation::class,
        ]);
    }
}
