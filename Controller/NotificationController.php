<?php

namespace Mgilet\NotificationBundle\Controller;

use Mgilet\NotificationBundle\Entity\NotifiableNotification;
use Mgilet\NotificationBundle\Entity\Notification;
use App\Entity\NotificationCloche;
use Mgilet\NotificationBundle\NotifiableInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Mgilet\NotificationBundle\Entity\NotifiableEntity;

/**
 * Class NotificationController
 * the base controller for notifications
 */
class NotificationController extends Controller
{
    /**
     * List of all notifications
     *
     * @Route("/{notifiable}", name="notification_list", methods={"GET"})
     * @param NotifiableInterface $notifiable
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($notifiable)
    {
        $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
        $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
        return $this->render('@MgiletNotification/notifications.html.twig', array(
            'notificationList' => $notificationList,
            'notifiableNotifications' => $notificationList // deprecated: alias for backward compatibility only
        ));
    }

    /**
     * Set a Notification as seen
     *
     * @Route("/{notifiable}/mark_as_seen/{notification}", name="notification_mark_as_seen", methods={"POST"})
     * @param int           $notifiable
     * @param Notification  $notification
     *
     * @return JsonResponse
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws \LogicException
     */
    public function markAsSeenAction($notifiable, $notification)
    {
        $manager = $this->get('mgilet.notification');
        $manager->markAsSeen(
            $manager->getNotifiableInterface($manager->getNotifiableEntityById($notifiable)),
            $manager->getNotification($notification),
            true
        );

        return new JsonResponse(true);
    }

    /**
     * Set a Notification as unseen
     *
     * @Route("/{notifiable}/mark_as_unseen/{notification}", name="notification_mark_as_unseen", methods={"POST"})
     * @param $notifiable
     * @param $notification
     *
     * @return JsonResponse
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws \LogicException
     */
    public function markAsUnSeenAction($notifiable, $notification)
    {
        $manager = $this->get('mgilet.notification');
        $manager->markAsUnseen(
            $manager->getNotifiableInterface($manager->getNotifiableEntityById($notifiable)),
            $manager->getNotification($notification),
            true
        );

        return new JsonResponse(true);
    }

    /**
     * Set all Notifications for a User as seen
     *
     * @Route("/{notifiable}/markAllAsSeen", name="notification_mark_all_as_seen", methods={"POST"})
     * @param $notifiable
     *
     * @return JsonResponse
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function markAllAsSeenAction($notifiable)
    {
        $manager = $this->get('mgilet.notification');
        $manager->markAllAsSeen(
            $manager->getNotifiableInterface($manager->getNotifiableEntityById($notifiable)),
            true
        );

        return new JsonResponse(true);
    }
    /**
     * Set a Notification as seen and link
     *
     * @Route("/{notifiable}/mark_as_seen_link/{notification}/{noti}", name="notification_mark_as_seen_link")
     * @param int           $notifiable
     * @param NotificationCloche  $notification
     *
     * @return JsonResponse
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws \LogicException
     */
   public function clocheAsSeenAction( $notifiable,NotificationCloche $notification, NotifiableNotification $noti)
    {
        $manager = $this->get('mgilet.notification');
        $manager->markAsSeen(
            $manager->getNotifiableInterface($manager->getNotifiableEntityById($notifiable)),
            $manager->getNotification($notification),
            true
        );

        return $this->redirect($noti->getLink());
    }
    
    /**
     * Set all Notifications for a User as seen per channel
     *
     * @Route("/{notifiable}/markAllAsSeenChannel/{channel}", name="notification_mark_all_as_seen_channel")
     * @param $notifiable
     *
     * @return JsonResponse
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    
    public function markAllAsSeenPerChannel($notifiable, $channel)
    {
        $manager = $this->get('mgilet.notification');
        $manager->markAllAsSeenPerChannel(
            $manager->getNotifiableInterface($manager->getNotifiableEntityById($notifiable)),
            $channel,
            true
        );

       return $this->redirect($this->generateUrl("param_home"));
    }
}
