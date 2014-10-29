<?php
/**
 * Created by PhpStorm.
 * User: sparx
 * Date: 24/10/14
 * Time: 11:07
 */

namespace Blog\Document;


use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/** @ODM\Document(collection="message") */
class Message implements InputFilterAwareInterface {

    protected $inputFilter;

    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $poster;

    /** @ODM\Field(type="string") */
    private $text;

    /** @ODM\Field(type="mongodate") */
    private $created;

    /** @ODM\Field(type="string") */
    private $chatId;

    /**
     * @param $property
     * @return mixed
     */
    public function __get($property) {
        return $this->$property;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPoster() {
        return $this->poster;
    }

    /**
     * @param $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @param $poster
     */
    public function setPoster($poster) {
        $this->poster = $poster;
    }

    /**
     * @return mixed
     */
    public function getChatId() {
        return $this->chatId;
    }

    /**
     * @param mixed $chatId
     */
    public function setChatId($chatId) {
        $this->chatId = $chatId;
    }

    /**
     * @return mixed
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created) {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getText() {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text) {
        $this->text = $text;
    }

    /**
     * @return array
     */
    public function getProperties() {
        return get_class_vars(__CLASS__);
    }

    /**
     * @param $data
     */
    public function exchangeArray($data) {
        $this->id = (!empty($data['_id'])) ? $data['_id'] : null;
        $this->chatId = (!empty($data['chatId'])) ? $data['chatId'] : 1;
        $this->text = (!empty($data['text'])) ? $data['text'] : null;
        $this->poster = (!empty($data['poster'])) ? $data['poster'] : null;
        $this->created = (!empty($data['created'])) ? $data['created'] : new \MongoDate();
    }

    /**
     * @return array
     */
    public function getArrayCopy() {
        return get_object_vars($this);
    }

    /**
     * @return array
     */
    public function getObjectAsArray() {
        $array = array(
            'chatId' => $this->chatId,
            'poster' => $this->poster,
            'text' => $this->text,
            'created' => $this->created
        );
        return $array;
    }

    /**
     * @param InputFilterInterface $inputFilter
     * @return void|InputFilterAwareInterface
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    /**
     * @return InputFilter|InputFilterInterface
     */
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

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