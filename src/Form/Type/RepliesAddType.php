<?php declare(strict_types=1);

// src/Form/Type/RepliesAddType.php
namespace App\Form\Type;

use App\Form\Type\CommentAddType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class RepliesAddType extends CommentAddType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        
        $builder
             ->add('parent_comment_id', HiddenType::class, ['required' => true])
        ;
    }
}