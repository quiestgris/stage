<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ImageRepository;

final class WebsiteController extends AbstractController
{
    #[Route(name: 'app_home')]
    public function index(ImageRepository $imgRepository): Response
    {
        $images = $imgRepository->findAll();

        return $this->render("base.html.twig", [
            "images" => $images,
        ]
    );
    }

    #[Route('/portfolio', name: 'app_portfolio')]
    public function portfolio(ImageRepository $imgRepository): Response
    {
        $imgs = $imgRepository->findBySrc("portfolio");
        
        return $this->render("portfolio.html.twig", [
            "imgs" => $imgs,
        ]
    );
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(ImageRepository $imgRepository): Response
    {
        $images = $imgRepository->findAll();

        return $this->render("contact.html.twig", [
            "images" => $images,
        ]
    );
    }
}
