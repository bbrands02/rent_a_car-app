<?php


namespace App\Controller;


use App\Repository\CarRepository;
use App\Service\ObjectService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $session;

    /**
     * @Route("/")
     * @Template
     */
    public function indexAction(Request $req, EntityManagerInterface $em, CarRepository $carRepository)
    {
        $variables['title'] = "Home";
        $variables['cars'] = $carRepository->getAvailableCars(3);

        return $variables;
    }
}