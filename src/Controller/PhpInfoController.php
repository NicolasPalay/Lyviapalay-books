<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class PhpInfoController extends AbstractController
{
    /**
     * @Route("/phpinfo", name="phpinfo")
     */
    public function phpinfoAction()
    {
        return new Response('<html><body>'.phpinfo().'</body></html>');
    }
}