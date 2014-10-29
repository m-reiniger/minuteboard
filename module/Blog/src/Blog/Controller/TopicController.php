<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Blog\Form\TopicForm;
use Blog\Document\Topic;

class TopicController extends AbstractActionController {

    protected $documentManager;

    public function indexAction() {

        $dm = $this->getDocumentManager();
        $qb = $dm->createQueryBuilder('Blog\Document\Topic');
        $qb->sort('created', 'desc');

//        $messages= $qb->field('chatId')->equals("1")
//            ->getQuery()
//            ->execute();

        $topics = $qb->getQuery()->execute();

        //print_r($topics);

        $form = new TopicForm();
        $form->get('submit')->setValue('Submit');

        return new ViewModel(array(
            'topics' => $topics,
            'form' => $form,
        ));
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function addAction() {

        $form = new TopicForm();
        $form->get('submit')->setValue('Submit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $topic = new Topic();
            $form->setInputFilter($topic->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $topic->exchangeArray($form->getData());

                $dm = $this->getDocumentManager();
                $dm->persist($topic);
                $dm->flush();

                // Redirect to blog
                return $this->redirect()->toRoute('topic');
            }
        }
        return array('form' => $form);
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

