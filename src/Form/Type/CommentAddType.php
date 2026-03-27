<?php declare(strict_types=1);
// src/Form/Type/CommentAddType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Set the form method to POST and add fields for name, email, and comment
        $builder
            ->setMethod('POST')
            ->add('name', TextType::class, ['required' => true, 'label' => 'Name'])
            ->add('email', TextType::class, ['required' => true, 'label' => 'Email'])
            ->add('comment', TextareaType::class, ['required' => true, 'label' => 'Comment'])
        ;
    }
}