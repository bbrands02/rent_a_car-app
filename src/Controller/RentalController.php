<?php

namespace App\Controller;

use App\Entity\Rental;
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
use Dompdf\Dompdf;
use Dompdf\Options;


class RentalController extends AbstractController
{

    /**
     * @Route("/rentals")
     * @Template
     */
    public function indexAction(ObjectService $os)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $variables['title'] = 'Rentals';
        $variables['rentals'] = $os->getAll('rental', ['rentedBy' => $this->getUser()->getPerson()]);

        return $variables;
    }

    /**
     * @Route("/rentals/{id}")
     * @Template
     */
    public function rentalAction(ObjectService $os, $id)
    {
        $variables['rental'] = $os->getOne('rental', $id);
        $personsName = $variables['rental']->getRentedBy()->getFirstName();
        if ($variables['rental']->getRentedBy()->getMiddleName() != null) {
            $personsName .= ' ' . $variables['rental']->getRentedBy()->getMiddleName();
        }
        $personsName .= ' ' . $variables['rental']->getRentedBy()->getLastName();
        $variables['title'] = $personsName;

        $variables['breadcrumbs'][] = [
            'name'=>'Rentals',
            'path'=>'/rentals',
            'active'=>false
        ];
        $variables['breadcrumbs'][] = [
            'name'=>$variables['rental']->getId(),
            'active'=>true
        ];


        return $variables;
    }

    /**
     * @Route("/download_invoice/{id}")
     */
    public function downloadRentalAction(ObjectService $os, $id)
    {
        $variables['rental'] = $os->getOne('rental', $id);
        $personsName = $variables['rental']->getRentedBy()->getFirstName();
        if ($variables['rental']->getRentedBy()->getMiddleName() != null) {
            $personsName .= ' ' . $variables['rental']->getRentedBy()->getMiddleName();
        }
        $personsName .= ' ' . $variables['rental']->getRentedBy()->getLastName();

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        $dompdf = new Dompdf($pdfOptions);
        
        $html = $this->renderView('rental/pdf.html.twig', [
            'title' => "Rent a car rental",
            'name' => $personsName,
            'id' => $id,
            'carName' => $variables['rental']->getCar()->getName(),
            'startDate' => $variables['rental']->getStartDate()->format('d-m-y'),
            'endDate' => $variables['rental']->getEndDate()->format('d-m-y'),
            'price' => strval(($variables['rental']->getPrice() / 100)) . ' eu'
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);

    }
}
