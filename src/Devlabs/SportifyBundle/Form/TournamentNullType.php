<?php

namespace Devlabs\SportifyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TournamentNullType extends AbstractType
{
    protected $data;
    protected $buttonAction;

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    /**
     * Build the tournament form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->data = $options['data']['tournament_id'];
        $this->buttonAction = $options['data']['button_action'];

        $builder
            ->add('id', HiddenType::class, array(
                'data' => $this->data
            ))
            ->add('action', HiddenType::class, array(
                'data' => $this->buttonAction
            ))
            ->add('button', SubmitType::class, array(
                'label' => $this->buttonAction
            ))
        ;
    }
}
