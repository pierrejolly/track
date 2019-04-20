<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Tag;

use AppBundle\Form\Type\TagType;

/**
 * Tag controller.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 *
 * @Route("/tag")
 */
class TagController extends Controller
{
    /**
     * List tag entities.
     *
     * @Security("is_granted('ROLE_VIEW_TAG')")
     * @Route("/", name="tag_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $datatable = $this->get('app.datatable.tag');
        $datatable->buildDatatable();

        return $this->render('tag/list.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * List tag entities results.
     *
     * @Security("is_granted('ROLE_VIEW_TAG')")
     * @Route("/list-results", name="tag_list_results")
     * @Method("GET")
     */
    public function listResultsAction()
    {
        $datatable = $this->get('app.datatable.tag');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        return $query->getResponse();
    }

    /**
     * Create a new tag entity.
     *
     * @Security("is_granted('ROLE_ADD_TAG')")
     * @Route("/new", name="tag_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag, [
            'validation_groups' => ['Default', 'New'],
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            // Add flash message
            $this->addFlash('info', 'Tag enregistré avec succès.');

            return $this->redirect($this->generateUrl('tag_list'));
        }

        return $this->render('tag/new.html.twig', [
            'tag' => $tag,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing tag.
     *
     * @Security("is_granted('ROLE_EDIT_TAG')")
     * @Route("/edit/{id}", name="tag_edit", options={"expose"=true})
     * @ParamConverter("tag", class="AppBundle:Tag")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Tag $tag)
    {
        $form = $this->createForm(TagType::class, $tag, [
            'validation_groups' => ['Default', 'Edit'],
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            // Add flash message
            $this->addFlash('info', 'Tag modifié avec succès.');

            return $this->redirect($this->generateUrl('tag_list'));
        }

        return $this->render('tag/edit.html.twig', [
            'tag' => $tag,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * Delete a tag entity.
     *
     * @Security("is_granted('ROLE_DELETE_TAG')")
     * @Route("/delete/{id}", name="tag_delete", options={"expose"=true})
     * @ParamConverter("tag", class="AppBundle:Tag")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Tag $tag)
    {
        $form = $this->createDeleteForm($tag);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tag);
            $em->flush();

            // Add flash message
            $this->addFlash('info', 'Tag supprimé avec succès.');

            return $this->redirect($this->generateUrl('tag_list'));
        }

        return $this->render('tag/_delete.html.twig', [
            'tag' => $tag,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a tag entity.
     *
     * @param Tag $tag
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Tag $tag)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tag_delete', ['id' => $tag->getId()]))
            ->add('submit', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, ['label' => 'Supprimer'])
            ->getForm()
        ;
    }
}
