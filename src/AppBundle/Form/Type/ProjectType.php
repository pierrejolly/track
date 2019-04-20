<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use AppBundle\Entity\Project;

/**
 * Project form.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 */
class ProjectType extends AbstractType
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
            ->add('client', TextType::class, [
                'label'    => 'Client',
                'required' => true,
            ])
            ->add('color', TextType::class, [
                'label'    => 'Couleur',
                'required' => true,
                'attr'     => [
                    'class' => 'color-picker',
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
            'data_class' => Project::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_project';
    }
}
