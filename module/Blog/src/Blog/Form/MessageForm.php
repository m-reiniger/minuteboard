<?php
/**
 * Created by PhpStorm.
 * User: sparx
 * Date: 23/10/14
 * Time: 14:54
 */

namespace Blog\Form;

use Zend\Form\Form;

class MessageForm extends Form{

    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('message');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'poster',
            'type' => 'Text',
            'options' => array(
                'label' => 'Name',
            )
        ));
        $this->add(array(
            'name' => 'text',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Message',
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Submit',
                'id' => 'submitbutton',
            )
        ));
    }
} 