<?php
/**
 * Created by PhpStorm.
 * User: sparx
 * Date: 24/10/14
 * Time: 13:49
 */

namespace Blog\Form;

use Zend\Form\Form;

class TopicForm extends Form{

    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('topic');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'createdBy',
            'type' => 'Text',
            'options' => array(
                'label' => 'Your name',
            )
        ));
        $this->add(array(
            'name' => 'topic',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Topic',
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