<?php

namespace App\Controller\Admin;

use App\Entity\Picture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/picture', name: 'picture_')]
//#[IsGranted('ROLE_ADMIN')]
class PicturesController extends AbstractController
{

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Request $request, EntityManagerInterface $entityManager, Picture $picture): Response
    {
        $entityManager->remove($picture);
        $entityManager->flush();

        $imagePath = 'assets/uploads/pictures/' . $picture->getUrlName();
        $imagePathMini = 'assets/uploads/pictures/mini/250x250-' . $picture->getUrlName();
        if (file_exists($imagePath)) {
            unlink($imagePath);
            unlink($imagePathMini);
        }

        return $this->redirectToRoute('admin_product_edit', ['slug' => $picture->getProduct()
            ->getSlug()

        ]);
    }



}