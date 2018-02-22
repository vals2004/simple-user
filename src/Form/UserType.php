<?php

namespace SimpleUser\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class LoginType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'data' => $lastUsername,
            ])
            ->add('_password', PasswordType::class, [
                'required' => true,
                'label' => 'Password',
            ])
            ->add('submit', SubmitType::class, ['label' => 'Sign In']);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return '';
    }
}