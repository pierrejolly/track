<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Task;

use AppBundle\Form\Type\TaskType;

/**
 * Task controller.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 *
 * @Route("/task")
 */
class TaskController extends Controller
{
    /**
     * List task entities.
     *
     * @Security("is_granted('ROLE_VIEW_TASK')")
     * @Route("/", name="task_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $datatable = $this->get('app.datatable.task');
        $datatable->buildDatatable();

        return $this->render('task/list.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * List task entities results.
     *
     * @Security("is_granted('ROLE_VIEW_TASK')")
     * @Route("/list-results", name="task_list_results")
     * @Method("GET")
     */
    public function listResultsAction()
    {
        $datatable = $this->get('app.datatable.task');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        // Get task of current user only
        if (false) {
            $callback = function($qb) {
                $qb->andWhere('task.user = :user');
                $qb->setParameter('user', $this->getUser());
            };
            $query->addWhereAll($callback);
        }

        return $query->getResponse();
    }

    /**
     * Create a new task entity.
     *
     * @Security("is_granted('ROLE_ADD_TASK')")
     * @Route("/new", name="task_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $task = new Task();
        $task->setUser($this->getUser());

        $form = $this->createForm(TaskType::class, $task, [
            'validation_groups' => ['Default', 'New'],
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            // Add flash message
            $this->addFlash('info', 'Tache enregistrée avec succès.');

            return $this->redirect($this->generateUrl('task_list'));
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing task.
     *
     * @Security("is_granted('ROLE_EDIT_TASK')")
     * @Route("/edit/{id}", name="task_edit", options={"expose"=true})
     * @ParamConverter("task", class="AppBundle:Task")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Task $task)
    {
        $form = $this->createForm(TaskType::class, $task, [
            'validation_groups' => ['Default', 'Edit'],
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            // Add flash message
            $this->addFlash('info', 'Tache modifiée avec succès.');

            return $this->redirect($this->generateUrl('task_list'));
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * Delete a task entity.
     *
     * @Security("is_granted('ROLE_DELETE_TASK')")
     * @Route("/delete/{id}", name="task_delete", options={"expose"=true})
     * @ParamConverter("task", class="AppBundle:Task")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Task $task)
    {
        $form = $this->createDeleteForm($task);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();

            // Add flash message
            $this->addFlash('info', 'Tache supprimée avec succès.');

            return $this->redirect($this->generateUrl('task_list'));
        }

        return $this->render('task/_delete.html.twig', [
            'task' => $task,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a task entity.
     *
     * @param Task $task
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Task $task)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('task_delete', ['id' => $task->getId()]))
            ->add('submit', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, ['label' => 'Supprimer'])
            ->getForm()
        ;
    }
}
