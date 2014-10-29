<?php
/**
 * Created by PhpStorm.
 * User: sparx
 * Date: 24/10/14
 * Time: 12:08
 */

namespace Blog\Document;


use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/** @ODM\Document(collection="topic") */
class Topic implements InputFilterAwareInterface {

    protected $inputFilter;

    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $topic;

    /** @ODM\Field(type="timestamp") */
    private $created;

    /** @ODM\Field(type="string") */
    private $createdBy;

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
    public function getCreatedBy() {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy) {
        $this->createdBy = $createdBy;
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
    public function getTopic() {
        return $this->topic;
    }

    /**
     * @param mixed $topic
     */
    public function setTopic($topic) {
        $this->topic = $topic;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
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
        $this->topic = (!empty($data['topic'])) ? $data['topic'] : null;
        $this->created = (!empty($data['created'])) ? $data['created'] : time();
        $this->createdBy = (!empty($data['createdBy'])) ? $data['createdBy'] : time();
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
            'topic' => $this->topic,
            'created' => $this->created,
            'createdBy' => $this->createdBy,
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
                'name' => 'topic',
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
                            'max' => 256,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'createdBy',
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

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
} 