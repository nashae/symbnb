<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Classe de pagination
 * entityclass doit etre defini lors de l'instanciation
 */
class Paginator
{
    /**
     * Entité sur laquelle on effectue la pagination
     *
     * @var string
     */
    private $entityClass;

    /**
     * Nombre d'enregistrement à récuperer
     *
     * @var integer
     */
    private $limit = 10;

    /**
     * Page sur laquelle on se trouve
     *
     * @var integer
     */
    private $currentPage = 1;

    /**
     * Doctrine manager, permettant de trouver le repo dont on a besoin
     *
     * @var ObjectManager
     */
    private $manager;

    /**
     * Moteur template twig, permet de generer le rendu de la pagination
     *
     * @var Twig\Environment
     */
    private $twig;

    /**
     * Routé utilisée pour les boutons de navigation
     *
     * @var string
     */
    private $route;

    /**
     * Chemin vers le template contenant la pagination
     *
     * @var string
     */
    private $templatePath;

    /**
     * Constructeur du paginator
     * 
     * $templatePath est à configurer dans services.yaml 
     *
     * @param ObjectManager $manager
     * @param Environment $twig
     * @param RequestStack $request
     * @param string $templatePath
     */
    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request, $templatePath)  //on accede au manager du repositoty grace au constructeur
    {
        //on récupere le nom de la route à utiliser à partir des attributs de la requete actuelle
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        //autres initialisations
        $this->manager = $manager;
        $this->twig = $twig;
        $this->templatePath = $templatePath;
    }

    
    /**
     * Permet d'afficher le rendu de la navigation au sein d'un template twig
     * 
     * twig->display(templatePath): affiche le template defini dans service.yaml
     * page : page actuelle
     * pages: nombre total de pages
     * route: route utilisée pour les liens de navigations
     *
     * @return void
     */
    public function display()
    {
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }

    /**
     * Permet de recuperer le nom de pages qui existe sur une entité particuliere
     * 
     * utilise objectmanager pour recuperer le repo
     * utilise le findall du repo pour recuperer le nombre total d'entrée
     * fonction ceil pour recuperer le nombre total/limit arrondi à l'unité superieure
     * 
     * @trows Exception si $entityClass non défini
     *
     * @return int
     */
    public function getPages()
    {
        if(empty($this->entityClass)){
            throw new Exception("Vous n'avez pas specifié l'entité sur laquelle on doit paginer");
        }
        //connaitre le total des enregistrements de la table
        $total = count($this->manager->getRepository($this->entityClass)->findAll());
        //calculer le ceil de nbpages/limit
        return ceil($total / $this->limit);
        
    }

    /**
     * Permet de récupérer les données paginées pour un entité spécifique
     * 
     * recupere l'entité grace à ObjectManager
     * utilise findBy du repository pour definir la recherche
     * 
     * $trows Exception si $entityClass non definie
     *
     * @return array
     */
    public function getData()
    {
        if(empty($this->entityClass)){
            throw new Exception("Vous n'avez pas specifié l'entité sur laquelle on doit paginer");
        }
        // calcul de l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;
        //on recupere le repository de l'entity, et on defini ce qui doit etre cherché
        return $this->manager->getRepository($this->entityClass)
                             ->findBy([], ['id' => 'DESC'], $this->limit, $offset);
    }

    public function setPage($Page)
    {
        $this->currentPage = $Page;
        return $this;
    }

    public function getPage()
    {
        return $this->currentPage;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }


    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

   

    /**
     * Get the value of route
     */ 
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set the value of route
     *
     * @return  self
     */ 
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get the value of templatepath
     */ 
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * Set the value of templatepath
     *
     * @return  self
     */ 
    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;

        return $this;
    }
}