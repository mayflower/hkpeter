<?php

namespace elseym\HKPeterBundle\Form\Type;

/**
 * Class AddKeyType
 */
class AddKeyType extends \Symfony\Component\Form\AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('armoredKey','textarea', array(
                'mapped' => false,
            ))
            ->add('save', 'submit')
        ;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'add_key';
    }

}
