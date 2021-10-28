<?php

namespace App\Controller;

use App\Entity\Car;
use MongoDB\Driver\Exception\ExecutionTimeoutException;
use Symfony\Component\Validator\Constraints\Date;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ObjectService;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\CarRepository;


class CarController extends AbstractController
{

    /**
     * @Route("/cars")
     * @Template
     */
    public function indexAction(ObjectService $os, CarRepository $carRepository)
    {
        $variables['title'] = 'Cars';
        $variables['cars'] = $os->getAll('car');


        return $variables;
    }

    /**
     * @Route("/cars/{id}")
     * @Template
     */
    public function carAction(ObjectService $os, $id)
    {
        $variables['car'] = $os->getOne('car', $id);
        $variables['title'] = $variables['car']->getName();

        $variables['breadcrumbs'][] = [
            'name' => 'Cars',
            'path' => '/cars',
            'active' => false
        ];
        $variables['breadcrumbs'][] = [
            'name' => $variables['car']->getName(),
            'active' => true
        ];

        return $variables;
    }

    /**
     * @Route("/rent-car/{id}")
     */
    public function rentAction(Request $req, ObjectService $os, $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($req->isMethod('POST')) {
            $object = $req->request->all();

            if ((empty($object['startDate']) || empty($object['endDate'])) &&
                (strtotime($object['endDate']) < strtotime($object['startDate']))) {
                // Needs to be a flash with a return
                throw new \Exception('Incomplete post');
            }

            $object['startDate'] = new \DateTime($object['startDate']);
            $object['endDate'] = new \DateTime($object['endDate']);

            if ($object['startDate']->getTimestamp() > $object['endDate']->getTimestamp()) {
                $differenceInDays = 1;
            } else {
                $differenceInDays = intval((($object['endDate']->getTimestamp() - $object['startDate']->getTimestamp()) / (1000 * 3600 * 24) * 1000));
            }
            if ($differenceInDays == 0) {
                $differenceInDays = 1;
            }


            $car = $os->getOne('car', $id);


            $object['price'] = $differenceInDays * $car->getPrice();
            $object['car'] = $id;
            $object['status'] = 'pending';
            $object['rentedBy'] = $this->getUser()->getPerson();

            $object['type'] = 'rental';

            $saveCar = [];
            $saveCar['id'] = $id;
            $saveCar['type'] = 'car';
            $saveCar['rentable'] = $car->getRentable() - 1;
            $saveCar = $os->uploadObject($saveCar);

            $os->uploadObject($object);
        }

        return $this->redirectToRoute('app_rental_index');
    }
}
