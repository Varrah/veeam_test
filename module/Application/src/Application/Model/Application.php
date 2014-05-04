<?php
/**
 * Created by JetBrains PhpStorm.
 * User: danil
 * Date: 4/30/14
 * Time: 2:09 AM
 */

namespace Application\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Application
{

    protected $inputFilter;

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(
                array(
                     'name'        => 'division',
                     'required'    => false,
                     'allow_empty' => true
                )
            );

            $inputFilter->add(
                array(
                     'name'        => 'language',
                     'required'    => false,
                     'allow_empty' => true
                )
            );

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}