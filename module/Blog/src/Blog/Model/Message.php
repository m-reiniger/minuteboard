<?php
/**
 * Created by PhpStorm.
 * User: sparx
 * Date: 23/10/14
 * Time: 11:17
 */

namespace Blog\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Message implements InputFilterAwareInterface {

    public $id;
    public $chatId;
    public $text;
    public $poster;
    public $created;

    protected $inputFilter;


    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->chatId = (!empty($data['chatId'])) ? $data['chatId'] : null;
        $this->text = (!empty($data['text'])) ? $data['text'] : null;
        $this->poster = (!empty($data['poster'])) ? $data['poster'] : null;
        $this->created = (!empty($data['created'])) ? $data['created'] : null;
    }


    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'id',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'poster',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 32,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'text',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 4096,
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }


} 