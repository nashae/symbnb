<?php 
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @route("/bonjour/{prenom}/age/{age}", name="hello")
     * @route("/bonjour", name="hello_base")
     * @route("/bonjour/{age}", name="hello_age")
     * 
     * montre la page qui dit bonjour
     * @return void
     */
    public function hello($prenom = 'anonyme', $age = 0)
    {
        return $this->render(
            'hello.html.twig',
            [
                'prenom' => $prenom,
                'age' => $age
            ]
        );
    }

    /**
     * @Route("/", name="homepage")
     */
    public function home()
    {
        $prenoms = ['david' => 20, 'john' => 40, 'luc' => 70];
        return $this->render(
            'home.html.twig',
            [
                'title' => "bonjour",
                'age' => 31,
                'tableau' => $prenoms
            ]
        );
        
    }
}