<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ObjectService;
use App\Entity\User;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @Template
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_security_user');
        }
        $variables['title'] = 'Login';

        // get the login error if there is one
        $variables['error'] = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $variables['lastUsername'] = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', $variables);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="app_register")
     * @Template
     */
    public function registerAction()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_security_user');
        }
        $variables['title'] = 'Sign up';

        return $variables;
    }

    /**
     * @Route("/me")
     * @Template
     */
    public function userAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $variables['title'] = 'Profile';

        return $variables;
    }

    /**
     * @Route("/create-user")
     */
    public function makeUserAction(Request $req, EntityManagerInterface $em, ObjectService $os)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_security_user');
        }

        if ($req->isMethod('POST')) {
            $object = $req->request->all();

            if (isset($object['firstName']) && !empty($object['firstName'])
                && isset($object['lastName']) && !empty($object['lastName'])
                && isset($object['emails']) && !empty($object['emails'])
                && isset($object['password']) && !empty($object['password'])
                && isset($object['passwordConfirm']) && !empty($object['passwordConfirm'])) {

                foreach ($object['emails'] as $email) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                         throw new \Exception('Please make sure the email is a valid email format.');
                    }
                }

                if ($object['password'] === $object['passwordConfirm']) {

                    // Make User
                    $user['type'] = 'User';
                    $user['email'] = $object['emails'][0];
                    $user['password'] = $object['password'];

                    $user = $os->uploadObject($user);

                    // Make Person
                    $person['type'] = 'Person';
                    $person['firstName'] = $object['firstName'];
                    if (!empty($object['middleName'])) {
                        $person['middleName'] = $object['middleName'];
                    }
                    $person['lastName'] = $object['lastName'];
                    $person['emails'] = $object['emails'];
                    $person['phoneNumbers'] = $object['phoneNumbers'];
                    $person['user'] = $user;

                    $os->uploadObject($person);

                } else {
                    throw new \Exception('Please make sure the passwords are identical.');
                }
            } else {
                throw new \Exception('Please fill in all required fields.');
            }
        }

        return $this->redirectToRoute('app_login');
    }


}
