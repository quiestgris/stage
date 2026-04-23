<?php

namespace App\Form;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class SignUpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                "attr" => [
                    'class' => 'form-control',
                    'minLength' => '2',
                    'maxLength' => '180',
                    
                ],
                "required" => true,
                'label' => 'Address email',
                'label_attr' => [
                    'class' => 'form_label'
                ],
                "constraints" => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Length(['min' => 2, 'max' => 180]) 
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Mot de passe',
                ],
                "required" => true,
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => "Confirmation du mot de passe"
                ],
                "invalid_message" => "Les mot de passe ne correspondent pas"
            ])
            
            ->add('submit', SubmitType::class, [
                'attr' => [
                    "class" => 'btn'
                ],
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
            'csrf_protection' => true,      
            'csrf_field_name' => '_token',  
            'csrf_token_id'   => 'signup_item',
        ]);
    }
}
