<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * Permet d'afficher le dashboard
     * 
     * @Route("/admin", name="admin_dashboard")
     * 
     */
    public function index(EntityManagerInterface $manager, StatsService $statsService)
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => [
                'users' => $statsService->getUsersCount(),
                'ads' => $statsService->getAdsCount(),
                'bookings' => $statsService->getBookingsCount(),
                'comments' => $statsService->getCommentsCount()
            ],
            'bestAds' => $statsService->getAdsStats('DESC'),
            'worstAds' => $statsService->getAdsStats('ASC')
        ]);
    }
}
