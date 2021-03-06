<?php

namespace SimpleUser\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    protected $dataClass;

    /**
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->dataClass = $class;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'label' => 'Password',
            ])
            ->add('submit', SubmitType::class, ['label' => 'Sign In']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
            'validation_groups' => ['Default'],
        ]);
    }
    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return '';
    }
}