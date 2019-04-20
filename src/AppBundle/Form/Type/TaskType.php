<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Task;

/**
 * Task form.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 */
class TaskType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'    => 'Nom',
                'required' => true,
            ])
            ->add('project', EntityType::class, [
                'label'        => 'Projet',
                'required'     => true,
                'class'        => 'AppBundle:Project',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->getEnabled();
                },
            ])
            ->add('date', DateType::class, [
                'label'    => 'Date',
                'required' => true,
                'widget'   => 'single_text',
                'format'   => 'dd/MM/yyyy',
                'html5'    => false,
                'attr'     => [
                    'class' => 'datepicker',
                ],
            ])
            ->add('duration', IntegerType::class, [
                'label'    => 'Durée',
                'required' => true,
                'help'     => 'En heure',
            ])
            ->add('tags', EntityType::class, [
                'label'        => 'Tags',
                'required'     => false,
                'class'        => 'AppBundle:Tag',
                'choice_label' => 'name',
                'multiple'     => true,
                'attr'         => [
                    'class' => 'select2',
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_task';
    }
}
