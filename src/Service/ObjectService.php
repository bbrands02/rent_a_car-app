<?php


namespace App\Service;

use App\Entity\Car;
use App\Entity\User;
use App\Entity\Person;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class ObjectService extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $pe;
    /**
     * @var SerializerInterface
     */
    private $si;

    public function __construct
    (
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $pe,
        SerializerInterface $si
    )
    {
        $this->em = $em;
        $this->pe = $pe;
        $this->si = $si;
    }

    // Fetches the right repository for this type
    public function fetchRepo($type)
    {
        $type = "App\\Entity\\" . ucwords($type);
        $repo = $this->getDoctrine()->getRepository($type);

        return $repo;
    }

    // Fetches all objects of a given entity

    /**
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function getAll($type, $query = null, $limit = null)
    {
        $repo = $this->fetchRepo($type);

        // Actual fetching of objects
        if ($limit != null && $limit > 0) {

            if (isset($query)) {
                $objects = $repo->findBy($query, $limit);
            } else {
                $objects = $repo->findBy(array(), array(), $limit);
            }

            $criteria = new Criteria();
            $criteria->where(Criteria::expr()->neq('image', null));

            $objects = $repo->matching($criteria);
        } else {
            if (isset($query)) {
                $objects = $repo->findAll($query);
            } else {
                $objects = $repo->findAll();
            }
        }


        return $objects;
    }


    // Fetches one object of a given entity
    public function getOne($type, $id)
    {
        $repo = $this->fetchRepo($type);

        // Actual fetching of objects
        $object = $repo->find($id);

        return $object;
    }

    // Uploads object (needs type from html form)
    public function uploadObject($object)
    {
        $type = $object['type'];

        if (isset($object['id'])) {
            $repo = $this->fetchRepo($type);
            $currentObj = $repo->find($object['id']);
        } else {
            $currentObj = null;
        }

        if (!isset($obj['id'])) {
            $object['type'] = "App\\Entity\\" . ucwords($object['type']);
        }

        $newObject = $this->createProperties($object, $currentObj);

//        if (!isset($obj['id'])) {
        $this->em->persist($newObject);
//        }

        $this->em->flush();
//        if() {
//            $this->addFlash('succes', ucwords($type).' successfully created');
//        };

        return $newObject;
    }

    public function createProperties($obj, $currentObj)
    {
        if (isset($currentObj)) {
            $newObject = $currentObj;
        } else {
            $newObject = new $obj['type']();
        }

        // Universal properties
        !empty($obj['name']) ? $newObject->setName($obj['name']) : null;
        !empty($obj['description']) ? $newObject->setDescription($obj['description']) : null;

        // Properties for a Car
        !empty($obj['color']) ? $newObject->setColor($obj['color']) : null;
        if (!empty($obj['image'])) {
            // If img starts with '/image' it already exists so we don't create a new img
            if (substr($obj['image'], 0, 6) == '/image') {
                $newObject->setImage($obj['image']);
            } else {
                $uuid = Uuid::v4();
                move_uploaded_file($obj['image'], 'images/' . $uuid . '.jpg');
                $obj['image'] = '/images/' . $uuid . '.jpg';
                $newObject->setImage($obj['image']);
            }
        }

        // Properties for a User
        !empty($obj['email']) ? $newObject->setEmail($obj['email']) : null;
        !empty($obj['password']) ? $newObject->setPassword($this->pe->encodePassword($newObject, $obj['password'])) :
            null;

        // Properties for a Person
        !empty($obj['firstName']) ? $newObject->setFirstName($obj['firstName']) : null;
        !empty($obj['middleName']) ? $newObject->setMiddleName($obj['middleName']) : null;
        !empty($obj['lastName']) ? $newObject->setLastName($obj['lastName']) : null;
        !empty($obj['emails']) ? $newObject->setEmails($obj['emails']) : null;
        !empty($obj['phoneNumbers']) ? $newObject->setPhoneNumbers($obj['phoneNumbers']) : null;
        !empty($obj['user']) ? $newObject->setUser($obj['user']) : null;
        !empty($obj['features']) ? $newObject->setFeatures($obj['features']) : null;
        if (!empty($obj['price'])) {
            if ($obj['price'] < 1) {
                $newObject->setPrice(0);
            } else {
                $newObject->setPrice($obj['price']);
            }
        }
        if (!empty($obj['rentable'])) {
            if ($obj['rentable'] < 1) {
                $newObject->setRentable(0);
            } else {
                $newObject->setRentable($obj['rentable']);
            }
        }
        !empty($obj['startDate']) ? $newObject->setStartDate($obj['startDate']) : null;
        !empty($obj['endDate']) ? $newObject->setEndDate($obj['endDate']) : null;
        !empty($obj['status']) ? $newObject->setStatus($obj['status']) : null;
        !empty($obj['rentedBy']) ? $newObject->setRentedBy($obj['rentedBy']) : null;

        !empty($obj['car']) ? $newObject->setCar($this->getOne('car', $obj['car'])) : null;

        if (!isset($obj['id'])) {
            $newObject->setDateCreated(new \DateTime('now'));
        }
        $newObject->setDateModified(new \DateTime('now'));


        return $newObject;
    }

}