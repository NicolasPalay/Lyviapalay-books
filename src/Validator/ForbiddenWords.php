<?php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ForbiddenWords extends Constraint
{
    public $message = 'Le commentaire contient des mots interdits.';
}