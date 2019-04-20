<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\Type\Filter\DashboardFilterType;

/**
 * Project controller.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 */
class DashboardController extends Controller
{
    /**
     * @Route("/", name="dashboard", options={"expose"=true})
     * @Route("/", name="homepage", options={"expose"=true})
     */
    public function indexAction(Request $request)
    {
        // Form
        $form = $this->createForm(DashboardFilterType::class);
        $form->handleRequest($request);

        // Get form data
        $users    = $form->get('user')->getData();
        $projects = $form->get('project')->getData();
        $tags     = $form->get('tag')->getData();
        $fromDate = $form->get('fromDate')->getData();
        $toDate   = $form->get('toDate')->getData();

        // Get statistics
        $projectStatistic = $this->get('app.statistic.project')
            ->get($users, $projects, $tags, $fromDate, $toDate);
        $userStatistic    = $this->get('app.statistic.user')
            ->get($users, $projects, $tags, $fromDate, $toDate);
        $tagStatistic     = $this->get('app.statistic.tag')
            ->get($users, $projects, $tags, $fromDate, $toDate);

        return $this->render('dashboard/index.html.twig', [
            'form'              => $form->createView(),
            'project_statistic' => $projectStatistic,
            'user_statistic'    => $userStatistic,
            'tag_statistic'     => $tagStatistic,
        ]);
    }
}
