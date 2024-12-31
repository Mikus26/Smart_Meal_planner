<?php

namespace App\Controller\Settings;

use App\Entity\WeekPeriod;
use App\Form\settings\WeekPeriodType;
use App\Repository\WeekPeriodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/weekperiod')]
final class WeekPeriodController extends AbstractController
{
    #[Route(name: 'app_week_period_index', methods: ['GET'])]
    public function index(WeekPeriodRepository $weekPeriodRepository): Response
    {
        return $this->render('settings/week_period/index.html.twig', [
            'week_periods' => $weekPeriodRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_week_period_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $weekPeriod = new WeekPeriod();
        $form = $this->createForm(WeekPeriodType::class, $weekPeriod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($weekPeriod);
            $entityManager->flush();

            return $this->redirectToRoute('app_week_period_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('settings/week_period/new.html.twig', [
            'week_period' => $weekPeriod,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_week_period_show', methods: ['GET'])]
    public function show(WeekPeriod $weekPeriod): Response
    {
        return $this->render('settings/week_period/show.html.twig', [
            'week_period' => $weekPeriod,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_week_period_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WeekPeriod $weekPeriod, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WeekPeriodType::class, $weekPeriod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_week_period_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('settings/week_period/edit.html.twig', [
            'week_period' => $weekPeriod,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_week_period_delete', methods: ['POST'])]
    public function delete(Request $request, WeekPeriod $weekPeriod, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$weekPeriod->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($weekPeriod);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_week_period_index', [], Response::HTTP_SEE_OTHER);
    }
}
