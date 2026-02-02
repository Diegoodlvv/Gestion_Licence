<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerForm;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("home")]
final class HomeController extends AbstractController
{
    #[Route(name: 'app_home', methods: ['GET'])]
    public function index(): Response
    {


        return $this->render('home.html.twig');
    }
}
