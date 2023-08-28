<?php

namespace App\Validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ForbiddenWordsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $forbiddenWords = [
                              'spam', 'publicité', 'viagra', 'casino', 'offre spéciale',
                              'gagner de l\'argent rapidement', 'prêt', 'médicament',
                              'adulte', 'gratuit', 'enrichissez-vous rapidement', 'pornographie',
                              'escroquerie', 'vente de faux documents', 'malware', 'virus',
                              'hack', 'fraude', 'escroc', 'pharmacie en ligne', 'sexe',
                          ];

        foreach ($forbiddenWords as $word) {
            if (stripos($value, $word) !== false) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
                return;
            }
        }
    }
}
