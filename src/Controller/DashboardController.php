<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Person;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ObjectService;

/**
 * Class DashboardController
 * @package App\Controller
 * @Route("/dashboard")
 */
class DashboardController extends AbstractController
{

    /**
     * @Route("/")
     * @Template
     */
    public function indexAction(Request $req, EntityManagerInterface $em, ObjectService $os)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $variables['title'] = 'Dashboard';

        return $variables;
    }

    /**
     * @Route("/users")
     * @Template
     */
    public function usersAction(Request $req, EntityManagerInterface $em, ObjectService $os)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $variables['title'] = 'Dashboard | Users';
        $variables['users'] = $os->getAll('user');

        $variables['breadcrumbs'][] = [
            'name' => 'Dashboard',
            'path' => '/dashboard',
            'active' => false
        ];
        $variables['breadcrumbs'][] = [
            'name' => 'Users',
            'active' => true
        ];

        return $variables;
    }

    /**
     * @Route("/users/{id}")
     * @Template
     */
    public function userAction(Request $req, EntityManagerInterface $em, ObjectService $os, $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $variables['title'] = 'Dashboard | User';
        if ($id != 'new') {
            $variables['item'] = $os->getOne('user', $id);
        } else {
            $variables['item']['name'] = 'New user';
            $variables['item']['id'] = null;
        }
        if (is_array($variables['item'])) {
            $variables['title'] = ucwords($variables['item']['person']['firstName']). ' ' . ucwords($variables['item']['person']['lastName']);
        } else {
            $variables['title'] = ucwords($variables['item']->getPerson()->getFirstName()) . ' ' . ucwords($variables['item']->getPerson()->getLastName()) ;
        }

        return $variables;
    }

    /**
     * @Route("/cars")
     * @Template
     */
    public function carsAction(Request $req, EntityManagerInterface $em, ObjectService $os)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $variables['title'] = 'Dashboard | Cars';
        $variables['cars'] = $os->getAll('car');

        $variables['breadcrumbs'][] = [
            'name' => 'Dashboard',
            'path' => '/dashboard',
            'active' => false
        ];
        $variables['breadcrumbs'][] = [
            'name' => 'Cars',
            'active' => true
        ];
        return $variables;
    }

    /**
     * @Route("/cars/{id}")
     * @Template
     */
    public function carAction(Request $req, EntityManagerInterface $em, ObjectService $os, $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($id != 'new') {
            $variables['item'] = $os->getOne('car', $id);
        } else {
            $variables['item']['name'] = 'New car';
            $variables['item']['id'] = null;
        }
        if (is_array($variables['item'])) {
            $variables['title'] = ucwords($variables['item']['name']);
        } else {
            $variables['title'] = ucwords($variables['item']->getName());
        }

        $variables['breadcrumbs'][] = [
            'name' => 'Dashboard',
            'path' => '/dashboard',
            'active' => false
        ];
        $variables['breadcrumbs'][] = [
            'name' => 'Cars',
            'path' => '/dashboard/cars',
            'active' => false
        ];
        if ($id != 'new') {
            $variables['breadcrumbs'][] = [
                'name' => $variables['item']->getName(),
                'active' => true
            ];
        } else {
            $variables['breadcrumbs'][] = [
                'name' => 'New car',
                'active' => true
            ];
        }

        return $variables;
    }

    /**
     * @Route("/save-car")
     */
    public function saveCarAction(Request $req, EntityManagerInterface $em, ObjectService $os)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($req->isMethod('POST')) {
            $object = $req->request->all();

            if (empty($object['name'])) {
                // Needs to be a flash with a return
                throw new \Exception('Please fill in the name!');
            }

            $img = $req->files->get('image');

            if (isset($img) && !empty($img)) {
                $object['image'] = $img;

            }

            $object['type'] = 'car';


            $object = $os->uploadObject($object);

            return $this->redirectToRoute('app_dashboard_car', ['id' => $object->getId()]);
        }

        return $this->redirectToRoute('app_dashboard_cars');
    }

    /**
     * @Route("/rentals")
     * @Template
     */
    public function rentalsAction(Request $req, EntityManagerInterface $em, ObjectService $os)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $variables['title'] = 'Dashboard | Rentals';
        $variables['rentals'] = $os->getAll('rental');

        $variables['breadcrumbs'][] = [
            'name' => 'Dashboard',
            'path' => '/dashboard',
            'active' => false
        ];
        $variables['breadcrumbs'][] = [
            'name' => 'Rentals',
            'active' => true
        ];
        return $variables;
    }

    /**
     * @Route("/rentals/{id}")
     * @Template
     */
    public function rentalAction(Request $req, EntityManagerInterface $em, ObjectService $os, $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($id != 'new') {
            $variables['item'] = $os->getOne('rental', $id);
        } else {
            $variables['item']['name'] = 'New car';
            $variables['item']['id'] = null;
        }
        $variables['title'] = ucwords($variables['item']->getCar()->getName());

        $variables['breadcrumbs'][] = [
            'name' => 'Dashboard',
            'path' => '/dashboard',
            'active' => false
        ];
        $variables['breadcrumbs'][] = [
            'name' => 'Rentals',
            'path' => '/dashboard/rentals',
            'active' => false
        ];
        if ($id != 'new') {
            $variables['breadcrumbs'][] = [
                'name' => $variables['item']->getCar()->getName(),
                'active' => true
            ];
        } else {
            $variables['breadcrumbs'][] = [
                'name' => 'New car',
                'active' => true
            ];
        }

        return $variables;
    }

    /**
     * @Route("/save-rental/{id}")
     */
    public function saveRentalAction(Request $req, EntityManagerInterface $em, ObjectService $os, $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($req->isMethod('POST')) {
            $object = $req->request->all();

            $object['type'] = 'rental';


            $object = $os->uploadObject($object);

            return $this->redirectToRoute('app_dashboard_rental', ['id' => $object->getId()]);
        }

        return $this->redirectToRoute('app_dashboard_rentals');
    }

    /**
     * @Route("/delete")
     */
    public function deleteAction(Request $req, EntityManagerInterface $em, ObjectService $os)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($req->isMethod('POST')) {
            $object = $req->request->all();
            if (isset($object['type']) && !empty($object['type'])) {
                $repo = $this->getDoctrine()->getRepository("App\\Entity\\" . ucwords($object['type']));

                if (isset($object['id']) && !empty($object['id'])) {
                    $deletedObject = $repo->find($object['id']);

                    $em->remove($deletedObject);
                    $em->flush();
                }
            }
        }

        if (isset($object['id']) && !empty($object['type'])) {
            return $this->redirectToRoute($object['returnRoute']);
        } else {
            return $this->redirectToRoute('app_dashboard_index');
        }
    }
}
