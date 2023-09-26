<?php

namespace App\Services;

use App\Services\Listing;
use Symfony\Component\Form\FormInterface;

class CommentValidator
{
    public function validateComment(FormInterface $form,$content, Listing $listing)
    {
        $comment = $form->get($content)->getData();
        $interdits = $listing->getListing();

        foreach ($interdits as $interdit) {
            if (str_contains($comment, $interdit)) {
                return false;
            }
        }

        return true;
    }
}
