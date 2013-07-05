<?php

namespace Flash\Bundle\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Flash\Bundle\DefaultBundle\Entity\Event;
use Flash\Bundle\DefaultBundle\Entity\Calendar\CalendarEvent;
use Flash\Bundle\ApiBundle\RESTApi\RESTController;
use Flash\Bundle\ApiBundle\RESTApi\GenericRestApi;

/**
 * @Route("")
 */
class EventApiController extends RESTController implements GenericRestApi {

    /**
     * @Route("logged/api/account/{acc_id}/calendar/events/{month}/{year}",requirements={"acc_id" = "\d+"})
     * @Method({"GET"})
     */
    public function getCalendarEventsAction($acc_id, $year = NULL, $month = NULL) {

        $em = $this->getDoctrine()->getManager();
        $loggedAcc = $this->get('security.context')->getToken()->getUser();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($acc_id);



        if (NULL == $acc) {
            throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
        } else {
            if ($loggedAcc->equals($acc)) {
                if (NULL == $year || NULL == $month) {
                    $events = $em->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->findAllByAccount($acc);
                } else {
                    $events = $em->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')
                            ->getAllByYearAndMonth($acc_id, $year, $month);
                }
            } else {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException('Access denied');
            }
        }

        return $this->handle($this->getView($events));
    }

    /**
     * @Route("logged/api/calendar/events/{id}/confirmed",requirements={"id" = "\d+"})
     * @Method({"GET"})
     */
    public function getConfirmedEventsAction($id) {

        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->find($id);

        if (NULL == $event)
            return $this->handle($this->getView(array('error' => 'Not found.')));

        $events = $em->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->isConfirmed($id);

        return $this->handle($this->getView(array('result' => $events)));
    }

    /**
     * @Route("logged/api/account/{acc_id}/calendar/events/check_status",requirements={"acc_id" = "\d+"})
     * @Method({"POST"})
     */
    public function checkStatusAction($acc_id) {

        $em = $this->getDoctrine()->getManager();
        $events = $this->getRequest()->get('events');

        if (sizeof($events) == 0) {
            return $this->handle($this->getView(array('events' => 'Empty data')));
        }
        
        $eventStr = '';
        foreach ($events as $event) {
            $eventStr.= $event . ', ';
        }
        $eventStr = substr($eventStr, 0, -2);

        $eventsWithStatus = $em->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->getEventsStatus($acc_id, $eventStr);

        return $this->handle($this->getView(array('events' => $eventsWithStatus)));
    }

    /**
     * @Route("logged/api/calendar/events/{id}/check_status",requirements={"id" = "\d+"})
     * @Method({"POST"})
     */
    public function checkSingleEventStatusAction($id) {

        $em = $this->getDoctrine()->getManager();

        $st = $em->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->getSingleEventStatus($id);

        return $this->handle($this->getView(array('event' => $st)));
    }

    /**
     * @Route("logged/api/account/{acc_id}/calendar/events/{id}/confirm",requirements={"id" = "\d+", "acc_id" = "\d+"})
     * @Method({"POST"})
     */
    public function confirmEventAction($acc_id, $id) {

        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->find($id);

        if (NULL == $event)
            return $this->handle($this->getView(array('error' => 'Not found.')));

        $percent = $this->getRequest()->get('percent');

        if ($percent < 0 || $percent > 100 || !is_int($percent) || NULL == $percent) {
            return $this->handle($this->getView(array('error' => 'Data error')));
        }

        $loggedAcc = $this->get('security.context')->getToken()->getUser();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($acc_id);

        if ($loggedAcc->equals($acc)) {
            if ($em->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->confirm($acc_id, $id)) {
                $em->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->setPercent($acc_id, $id, $percent);
                return $this->handle($this->getView(array('success' => 'confirmed')));
            }
        } else {
            return $this->handle($this->getView(array('error' => 'Access denied.')));
        }
        return $this->handle($this->getView(array('error' => 'Event didnt confirm')));
    }

    /**
     * @Route("logged/api/account/{acc_id}/calendar/events/{id}/reject",requirements={"id" = "\d+", "acc_id" = "\d+"})
     * @Method({"POST"})
     */
    public function rejectEventAction($acc_id, $id) {

        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->find($id);

        if (NULL == $event)
            return $this->handle($this->getView(array('error' => 'Not found.')));

        $loggedAcc = $this->get('security.context')->getToken()->getUser();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($acc_id);

        if ($loggedAcc->equals($acc)) {
            if ($em->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->reject($acc_id, $id)) {
                return $this->handle($this->getView(array('success' => 'rejected')));
            }
        } else {
            return $this->handle($this->getView(array('error' => 'Access denied.')));
        }
        return $this->handle($this->getView(array('error' => 'Event didn\'t reject')));
    }

    /**
     * @Route("logged/api/account/{acc_id}/calendar/events/{id}/watch",requirements={"id" = "\d+", "acc_id" = "\d+"})
     * @Method({"POST"})
     */
    public function watchEventAction($acc_id, $id) {

        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->find($id);

        if (NULL == $event)
            return $this->handle($this->getView(array('error' => 'Not found.')));

        $loggedAcc = $this->get('security.context')->getToken()->getUser();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($acc_id);

        if ($loggedAcc->equals($acc)) {
            if ($em->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->watch($acc_id, $id)) {
                return $this->handle($this->getView(array('success' => 'watched')));
            }
        } else {
            return $this->handle($this->getView(array('error' => 'Access denied.')));
        }
        return $this->handle($this->getView(array('error' => 'Event didn\'t watch')));
    }

    /**
     * @Route("logged/api/account/{acc_id}/calendar/events/{id}/percent",requirements={"id" = "\d+", "acc_id" = "\d+"})
     * @Method({"POST"})
     */
    public function setPercentAction($acc_id, $id) {
        $percent = $this->getRequest()->get('percent');

        if ($percent < 0 || $percent > 100 || !is_int($percent)) {
            return $this->handle($this->getView(array('error' => 'Data error')));
        }
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->find($id);
        $percent = $this->getRequest()->get('percent');

        if ($percent < 0 || $percent > 100 || !is_int($percent)) {
            return $this->handle($this->getView(array('error' => 'Data error')));
        }

        if (NULL == $event)
            return $this->handle($this->getView(array('error' => 'Not found.')));

        $loggedAcc = $this->get('security.context')->getToken()->getUser();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($acc_id);

        if ($loggedAcc->equals($acc)) {
            if ($em->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->setPercent($acc_id, $id, $percent)) {
                return $this->handle($this->getView(array('success' => 'saved')));
            }
        } else {
            return $this->handle($this->getView(array('error' => 'Access denied.')));
        }
        return $this->handle($this->getView(array('error' => 'Event didn\'t save')));
    }

    /**
     * @Route("logged/api/account/{acc_id}/followers",requirements={"acc_id" = "\d+"})
     * @Method({"GET"})
     */
    public function getFollowersAction($acc_id) {

        $loggedAcc = $this->get('security.context')->getToken()->getUser();
        $acc = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Account')->find($acc_id);

        if ($loggedAcc->equals($acc) && $acc->getIsLeader()) {

            $followers = $acc->getFollowers()->getValues();
            return $this->handle($this->getView($followers));
        }
        return $this->handle($this->getView(array('error' => 'Access denied.')));
    }

    /**
     * @Route("logged/api/account/{acc_id}/calendar/events/{id}/share",requirements={"acc_id" = "\d+", "id" = "\d+"})
     * @Method({"PUT"})
     */
    public function shareEventAction($acc_id, $id) {

        $em = $this->getDoctrine()->getManager();
        $loggedAcc = $this->get('security.context')->getToken()->getUser();
        $acc = $em->getRepository('FlashDefaultBundle:Account')->find($acc_id);

        $event = $em->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->find($id);
        $event->setColor($this->getRequest()->get('color'));
        $event->setIsShared(true);

        if (NULL == $event)
            return $this->handle($this->getView(array('error' => 'Not found.')));

        if ($loggedAcc->equals($acc) && $acc->getIsLeader()) {

            //$followers = $acc->getFollowers()->getValues();
            $followersIdArray = $this->getRequest()->get('sharedFor');
            foreach ($followersIdArray as $id) {

                $follower = $em->getRepository('FlashDefaultBundle:Account')->find($id);
                if (NULL == $follower) {
                    return $this->handle($this->getView(array('error' => 'User not found.')));
                }
                $events = $follower->getCalendarEvents()->getValues();
                foreach ($events as $e) {
                    if ($event->equals($e)) {
                        return $this->handle($this->getView(array('error' => 'Event is already shared.')));
                    }
                }

                $follower->addCalendarEvent($event);

                $em->persist($follower);
                $em->persist($event);
                $em->flush();
            }
        } else {
            return $this->handle($this->getView(array('error' => 'Access denied.')));
        }
        return $this->handle($this->getView($event));
    }

    /**
     * @Route("logged/api/account/{acc_id}/calendar/events/{id}",requirements={"acc_id" = "\d+", "id" = "\d+"})
     * @Method({"PUT"})
     */
    public function updateCalendarEventAction($id) {

        $event = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->find($id);

        if (NULL != $event) {
            if ($this->get('security.context')->isGranted('EDIT', $event)) {
                return $this->handle($this->get('event_service')
                                        ->processCalendarEventForm($event));
            } else {
                return $this->handle($this->getView(array('error' => 'Access denied.')));
            }
        }
        return $this->handle($this->getView(array('error' => 'Not found.')));
    }

    /**
     * @Route("logged/api/account/{acc_id}/calendar/events/{id}",requirements={"acc_id" = "\d+", "id" = "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteCalendarEventAction($id) {

        $event = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Calendar\CalendarEvent')->find($id);

        if (NULL != $event) {
            if ($this->get('security.context')->isGranted('DELETE', $event)) {
                return $this->handle($this->get('event_service')->deleteCalendarEvent($event));
            } else {
                return $this->handle($this->getView(array('error' => 'Access denied.')));
            }
        }
        return $this->handle($this->getView(array('error' => 'Not found.')));
    }

    /**
     * @Route("logged/api/account/{acc_id}/calendar/events",requirements={"acc_id" = "\d+"})
     * @Method({"POST"})
     */
    public function postCalendarEventAction() {

        return $this->handle($this->get('event_service')
                                ->processCalendarEventForm(new CalendarEvent()));
    }

    /**
     * @Route("logged/api/feed/events/{for}/{to}",requirements={"for" = "\d+", "to" = "\d+"})
     * @Method({"GET"})
     */
    public function getFeedEventAction($for, $to) {

        if ($for < 0) {
            return $this->handle($this->getView(array('error'=>'Wrong data')));
        }
        
        $events = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Event')->findAllConfirmedInFeed($for, $to);

        return $this->handle($this->getView($events));
    }

    /**
     * @Route("moderator/api/feed/events")
     * @Method({"GET"})
     */
    public function getNotConfirmedFeedEventAction() {


        $events = $this->getDoctrine()->getManager()
                        ->getRepository('FlashDefaultBundle:Event')->findAllNotConfirmedInFeed();

        return $this->handle($this->getView($events));
    }

    /**
     * @Route("logged/api/feed/events")
     * @Method({"POST"})
     */
    public function postFeedEventAction() {

        $acc = $this->get('security.context')->getToken()->getUser();
        return $this->handle($this->get('event_service')
                                ->processFeedEventForm(new Event($acc)));
    }

    /**
     * @Route("moderator/api/feed/events/{id}")
     * @Method({"DELETE"})
     */
    public function deleteFeedEventAction($id) {

        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('FlashDefaultBundle:Event')->find($id);
        if (NULL === $event)
            return $this->handle($this->getView(array('error' => 'Not found')));

        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('ROLE_MODERATOR')) {
            return $this->handle($this->get('event_service')->delete($event));
        }
        return $this->handle($this->getView(array('error' => 'Access denied')));
    }

    /**
     * @Route("moderator/api/feed/events/{id}")
     * @Method({"PUT"})
     */
    public function putFeedEventAction($id) {

        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('FlashDefaultBundle:Event')->find($id);
        if (NULL === $event)
            return array('error' => 'Not found');

        return $this->handle($this->get('event_service')
                                ->processFeedEventForm($event));
    }

    ///////////////////////////////////////////////////////////////////////////////////

    /**
     * @Route("/logged/rest/api/events/group")
     * @Method({"POST"})
     */
    public function postAction() {

        $user = $this->get('security.context')->getToken()->getUser();
        $group = $user->getGroup();

        if ($this->get('security.context')->isGranted('EDIT', $group) &&
                $user->getGroup()->getNumberOfParty() >= $user->getGroup()->getMinimumUsersNumber()) {

            return $this->get('event_service')->processForm(new Event());
        } else {
            return array('error' => 'Access denied');
        }
    }

    /**
     * @Route("/logged/rest/api/events")
     * @Method({"GET"})
     */
    public function getAction($id = null) {

        $view = View::create();

        if (null != $id) {
            $event = $this->getDoctrine()->getManager()->getRepository('FlashDefaultBundle:Event')->find($id);
            if (NULL != $event) {
                $view->setData($event);
            } else {
                throw new \Symfony\Component\Translation\Exception\NotFoundResourceException('Not found');
            }
        } else {
            $events = $this->getDoctrine()->getManager()->getRepository('FlashDefaultBundle:Event')->findAll();
            $view->setData($events);
        }
        return $this->handle($view);
    }

    /**
     * @Route("/logged/rest/api/events/group/{id}")
     * @Method({"GET"})
     */
    public function getByGroupAction($id = null) {

        $em = $this->getDoctrine()->getManager();
        $view = View::create();
        $acc = $this->get('security.context')->getToken()->getUser();
        if (NULL != $id) {
            
        } else {
            $events = $em->getRepository('FlashDefaultBundle:Event')->getByGroup($acc->getGroup());
            $view->setData($events);
        }

        return $this->handle($view);
    }

    /**
     * @Route("/logged/rest/api/events/group/{id}")
     * @Method({"PUT"})
     */
    public function putAction($id) {

        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('FlashDefaultBundle:Event')->find($id);
        if (NULL === $event)
            return array('error' => 'Not found');

        $secContext = $this->get('security.context');

        if (FALSE === $secContext->isGranted('EDIT', $event))
            return array('error' => 'Hey! You don\'t have enought permissions.');

        return $this->get('event_service')->processForm($event);
    }

    public function deleteAction($id) {
        
    }

}