<?php

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
//use Symfony\Component\HttpFoundation\RequestStack;

class UserService

{
//     private $requestStack;

//     public function __construct(RequestStack $requestStack)
//     {
//         $this->requestStack = $requestStack;

        // Accessing the session in the constructor is *NOT* recommended, since
        // it might not be accessible yet or lead to unwanted side-effects
        // $this->session = $requestStack->getSession();
    //}
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    } 

    public function loggedInUser() 
    {
        return $this->security->getUser();
          
        }
}
    //public function someMethod(UserRepository $userrepository)
    //{
        //$session = $this->requestStack->getSession();

        // stores an attribute in the session for later reuse
        //$session->set('username', '$username');

        // gets an attribute by name
        //$userid = $session->get('id');

        // the second argument is the value returned when the attribute doesn't exist
        //$filters = $session->get('filters', []);

        // ...
    //}
