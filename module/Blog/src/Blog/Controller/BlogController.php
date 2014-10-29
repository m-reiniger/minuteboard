<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;

use Blog\Form\MessageForm;
use Blog\Document\Message;


class BlogController extends AbstractActionController {

    protected $documentManager;

    /**
     * @return array|ViewModel
     */
    public function indexAction() {

        $dm = $this->getDocumentManager();
        $qb = $dm->createQueryBuilder('Blog\Document\Message');
        $qb->sort('created', 'asc');
        //$qb->hydrate(false);

        $messages = $qb->getQuery()->execute();

        $form = new MessageForm();
        $form->get('submit')->setValue('Post');

        return new ViewModel(array(
            'messages' => $messages,
            'form' => $form,
        ));
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function postAction() {

        $response = $this->getResponse();
        $form = new MessageForm();
        $form->get('submit')->setValue('Post');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $message = new Message();
            $form->setInputFilter($message->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $message->exchangeArray($form->getData());

                $dm = $this->getDocumentManager();
                $dm->persist($message);
                $dm->flush();
            }
        }

        $response->setContent(Json::encode(array()));

        return $response;
    }

    /**
     *
     */
    public function getMessagesAction(){

        $response = $this->getResponse();
        $sec= $this->params()->fromPost('sec');
        $usec= $this->params()->fromPost('usec');
        $messages = array();

        $dm = $this->getDocumentManager();
        $qb = $dm->createQueryBuilder('Blog\Document\Message');
        $qb->hydrate(false);

        $cursor = $qb->sort('created', 'asc')
            ->field('created')->gt(new \MongoDate(intval($sec), intval($usec)))
            ->getQuery()
            ->execute();

        foreach ($cursor as $doc) {
            array_push($messages, $doc);
        }

        $response->setContent(Json::encode($messages));

        return $response;
    }

    /**
     * @return ViewModel
     */
    public function rateAction() {
        return new ViewModel();
    }

    /**
     * @return array|object
     */
    public function getDocumentManager() {
        if(!$this->documentManager){
            $this->documentManager = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        }
        return $this->documentManager;
    }


}

