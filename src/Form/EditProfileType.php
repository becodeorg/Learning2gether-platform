<?php


namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('avatar', FileType::class, [
                'required' => false,
                'attr'=>
                    array(
                        'placeholder'=>'Avatar',
                        'class'=>'uploader'),
                'mapped' => false,
                'label' => 'Upload image',
                'constraints' => [new Image([
                    'maxSize' => '5m',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                    ]
                ])]
            ])
            ->add('username', null, [
                'attr'=>
                    array(
                        'placeholder'=>'Username',
                        'class'=>'registerInput'),
                'label'=> false
            ])
            ->add('name', null, [
                'attr'=>
                    array(
                        'placeholder'=>'Name',
                        'class'=>'registerInput'),
                'label'=> false
            ])
            ->add('email', null, [
                'attr'=>
                    array(
                        'placeholder'=>'Email',
                        'class'=>'registerInput'),
                'label'=> false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}