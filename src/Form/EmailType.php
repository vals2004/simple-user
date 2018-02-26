<?php

namespace SimpleUser\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType as EmailFieldType;
class EmailType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailFieldType::class, [
                'required' => true,
                'label' => 'Email',
            ])
            ->add('submit', SubmitType::class, ['label' => 'Reset password']);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return '';
    }
}