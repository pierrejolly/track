<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\User;

use AppBundle\Form\Type\UserType;

/**
 * User controller.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * List user entities.
     *
     * @Security("is_granted('ROLE_VIEW_USER')")
     * @Route("/", name="user_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $datatable = $this->get('app.datatable.user');
        $datatable->buildDatatable();

        return $this->render('user/list.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * List user entities results.
     *
     * @Security("is_granted('ROLE_VIEW_USER')")
     * @Route("/list-results", name="user_list_results")
     * @Method("GET")
     */
    public function listResultsAction()
    {
        $datatable = $this->get('app.datatable.user');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        return $query->getResponse();
    }

    /**
     * Edit an existing user entity.
     *
     * @Security("is_granted('ROLE_EDIT_USER')")
     * @Route("/edit/{id}", name="user_edit", options={"expose"=true})
     * @ParamConverter("user", class="AppBundle:User")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user, [
            'validation_groups' => ['Default', 'Edit'],
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            // Add flash message
            $this->addFlash('info', 'Utilisateur modifié avec succès.');

            return $this->redirect($this->generateUrl('user_list'));
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
