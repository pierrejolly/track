<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Project;

use AppBundle\Form\Type\ProjectType;

/**
 * Project controller.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 *
 * @Route("/project")
 */
class ProjectController extends Controller
{
    /**
     * List project entities.
     *
     * @Security("is_granted('ROLE_VIEW_PROJECT')")
     * @Route("/", name="project_list")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        $datatable = $this->get('app.datatable.project');
        $datatable->buildDatatable();

        return $this->render('project/list.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * List project entities results.
     *
     * @Security("is_granted('ROLE_VIEW_PROJECT')")
     * @Route("/list-results", name="project_list_results")
     * @Method("GET")
     */
    public function listResultsAction()
    {
        $datatable = $this->get('app.datatable.project');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        return $query->getResponse();
    }

    /**
     * Displays an existing project.
     *
     * @Security("is_granted('ROLE_VIEW_PROJECT')")
     * @Route("/show/{id}", name="project_show", options={"expose"=true})
     * @ParamConverter("project", class="AppBundle:Project")
     * @Method("GET")
     */
    public function showAction(Request $request, Project $project)
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * Create a new project entity.
     *
     * @Security("is_granted('ROLE_ADD_PROJECT')")
     * @Route("/new", name="project_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project, [
            'validation_groups' => ['Default', 'New'],
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            // Add flash message
            $this->addFlash('info', 'Projet enregistré avec succès.');

            return $this->redirect($this->generateUrl('project_list'));
        }

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing project.
     *
     * @Security("is_granted('ROLE_EDIT_PROJECT')")
     * @Route("/edit/{id}", name="project_edit", options={"expose"=true})
     * @ParamConverter("project", class="AppBundle:Project")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Project $project)
    {
        $form = $this->createForm(ProjectType::class, $project, [
            'validation_groups' => ['Default', 'Edit'],
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            // Add flash message
            $this->addFlash('info', 'Projet modifié avec succès.');

            return $this->redirect($this->generateUrl('project_list'));
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * Delete a project entity.
     *
     * @Security("is_granted('ROLE_DELETE_PROJECT')")
     * @Route("/delete/{id}", name="project_delete", options={"expose"=true})
     * @ParamConverter("project", class="AppBundle:Project")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Project $project)
    {
        $form = $this->createDeleteForm($project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();

            // Add flash message
            $this->addFlash('info', 'Projet supprimé avec succès.');

            return $this->redirect($this->generateUrl('project_list'));
        }

        return $this->render('project/_delete.html.twig', [
            'project' => $project,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a project entity.
     *
     * @param Project $project
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Project $project)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('project_delete', ['id' => $project->getId()]))
            ->add('submit', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, ['label' => 'Supprimer'])
            ->getForm()
        ;
    }
}
