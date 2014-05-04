<?php
/**
 * Created by JetBrains PhpStorm.
 * User: danil
 * Date: 4/29/14
 * Time: 3:39 AM
 */

namespace Application\Form;
use Zend\Form\Form;

class SearchForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->add(
            array(
                 'name'    => 'division',
                 'type'    => 'Zend\Form\Element\Select',
                 'options' => array(
                     'empty_option' => 'all',
                     'label'        => 'Division',
                 ),
            )
        );
        $this->add(
            array(
                 'name'    => 'language',
                 'type'    => 'Zend\Form\Element\Select',
                 'options' => array(
                     'empty_option' => 'all',
                     'label'        => 'Language',
                 ),
            )
        );
        $this->add(
            array(
                 'name'       => 'submit',
                 'type'       => 'Submit',
                 'attributes' => array(
                     'value' => 'Go',
                     'id'    => 'submitbutton',
                 ),
            )
        );
    }
}