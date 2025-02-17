<?php

namespace App\Form;

use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Regex("/^[a-zA-Z'-]+$/")
                ],
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Regex("/^[a-zA-Z'-]+$/")
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email()
                ],
            ])
            ->add('hireDate', DateType::class, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual('today')
                ],
            ])
            ->add('salary', MoneyType::class, [
                'currency' => false,
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual(100)
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
            'csrf_protection' => false
        ]);
    }
}