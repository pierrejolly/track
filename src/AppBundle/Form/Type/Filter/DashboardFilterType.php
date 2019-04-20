<?php

namespace AppBundle\Form\Type\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Dashboard filter form.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 */
class DashboardFilterType extends AbstractType
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('user', EntityType::class, [
                'label'        => 'Utilisateur(s)',
                'required'     => false,
                'class'        => 'AppBundle:User',
                'choice_label' => 'fullName',
                'multiple'     => true,
                'attr'         => [
                    'class' => 'select2',
                ],
            ])
            ->add('project', EntityType::class, [
                'label'        => 'Projet(s)',
                'required'     => false,
                'class'        => 'AppBundle:Project',
                'choice_label' => 'name',
                'multiple'     => true,
                'attr'         => [
                    'class' => 'select2',
                ],
            ])
            ->add('tag', EntityType::class, [
                'label'        => 'Tag(s)',
                'required'     => false,
                'class'        => 'AppBundle:Tag',
                'choice_label' => 'name',
                'multiple'     => true,
                'attr'         => [
                    'class' => 'select2',
                ],
            ])
            ->add('fromDate', DateType::class, [
                'label'    => 'Du',
                'required' => false,
                'widget'   => 'single_text',
                'format'   => 'dd/MM/yyyy',
                'html5'    => false,
                'data'     => new \DateTime('first day of this month'),
                'attr'     => [
                    'class' => 'datepicker',
                ],
            ])
            ->add('toDate', DateType::class, [
                'label'    => 'Au',
                'required' => false,
                'widget'   => 'single_text',
                'format'   => 'dd/MM/yyyy',
                'html5'    => false,
                'data'     => new \DateTime('last day of this month'),
                'attr'     => [
                    'class' => 'datepicker',
                ],
            ])
        ;

//        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
//            $form = $event->getForm();
//            if ($this->authorizationChecker->isGranted('ROLE_VIEW_USER')) {
//                $form->add('user', EntityType::class, [
//                    'label'        => 'Utilisateur(s)',
//                    'required'     => false,
//                    'class'        => 'AppBundle:User',
//                    'choice_label' => 'fullName',
//                    'multiple'     => true,
//                    'attr'         => [
//                        'class' => 'select2',
//                    ],
//                ]);
//            }
//        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
