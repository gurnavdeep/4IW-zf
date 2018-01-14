<?php

declare(strict_types=1);

namespace Meetup\Controller;

use Meetup\Repository\MeetupRepository;
use Meetup\Form\MeetupForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

final class IndexController extends AbstractActionController
{
    /**
     * @var MeetupRepository
     */
    private $meetupRepository;

    /**
     * @var MeetupForm
     */
    private $meetupForm;


    public function __construct(MeetupRepository $meetupRepository, MeetupForm $meetupForm)
    {
        $this->meetupRepository = $meetupRepository;
        $this->meetupForm = $meetupForm;
    }

    public function indexAction()
    {
        return new ViewModel([
            'meetup' => $this->meetupRepository->findAll(),
        ]);
    }

    public function addAction()
    {
        $form = $this->meetupForm;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());

            if($form->isValid()){
                $meetup = $this->meetupRepository->createMeetupFromNameAndDescription(
                    $form->getData()['title'],
                    $form->getData()['description'] ?? '',
                    $form->getData()['date_debut'],
                    $form->getData()['date_fin']
                );

                $this->meetupRepository->add($meetup);
                $this->redirect()->toRoute('meetup');
            }
        }

        $form->prepare();

        return new ViewModel([
            'form' => $form,
        ]);
    }


    public function removeAction()
    {
        $id = $this->params()->fromRoute('id', -1);
        $meetup = $this->meetupRepository->findOneById($id);

        $this->meetupRepository->remove($meetup);
        $this->redirect()->toRoute('meetup');
    }


    public function updateAction()
    {
        $form = $this->meetupForm;

        $id = $this->params()->fromRoute('id', -1);
        $meetup = $this->meetupRepository->findOneById($id);

        $old_data = [];
        $old_data['title'] = $meetup->getTitle();
        $old_data['description'] = $meetup->getDescription();
        $old_data['date_debut'] = $meetup->getDateDebut();
        $old_data['date_fin'] = $meetup->getDateFin();

        $form->setData($old_data);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $data = $this->params()->fromPost();

            if($form->isValid())
            {
                $this->meetupRepository->update($meetup, $data);
                $this->redirect()->toRoute('meetup');
            }
        }
        return new ViewModel([
            'form' => $form,
            'meetup' => $meetup,
        ]);
    }

    public function detailAction()
    {
        $id = $this->params()->fromRoute('id', -1);
        $meetup = $this->meetupRepository->findOneById($id);

        return new ViewModel([
            'meetup' => $meetup,
        ]);
    }
}
