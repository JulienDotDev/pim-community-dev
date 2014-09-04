<?php

namespace spec\Pim\Bundle\NotificationBundle\Manager;

use Oro\Bundle\UserBundle\Entity\UserManager;
use PhpSpec\ObjectBehavior;
use Pim\Bundle\NotificationBundle\Entity\Repository\UserNotificationRepository;
use Prophecy\Argument;
use Doctrine\ORM\EntityManager;
use Oro\Bundle\UserBundle\Entity\User;
use Pim\Bundle\NotificationBundle\Entity\UserNotification;
use Pim\Bundle\NotificationBundle\Entity\Notification;
use Pim\Bundle\NotificationBundle\Factory\NotificationFactory;

class UserNotificationManagerSpec extends ObjectBehavior
{
    function let(EntityManager $em, UserNotificationRepository $repository, NotificationFactory $factory, UserManager $userManager)
    {
        $this->beConstructedWith($em, $repository, $factory, $userManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Pim\Bundle\NotificationBundle\Manager\UserNotificationManager');
    }

    function it_can_create_a_notification(
        User $user,
        Notification $notification,
        UserNotification $userNotification,
        $em,
        $factory
    ) {
        $factory
            ->createNotification('Some message', 'success', Argument::any())
            ->shouldBeCalled()
            ->willReturn($notification);
        $factory->createUserNotification($notification, $user)
            ->shouldBeCalled()
            ->willReturn($userNotification);
        $em->persist($notification)->shouldBeCalled();
        $em->persist($userNotification)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $this->notify([$user], 'Some message')->shouldReturn($this);
    }

    function it_can_create_multiple_notifications(
        User $user,
        User $user2,
        Notification $notification,
        UserNotification $userNotification,
        $em,
        $factory
    ) {
        $factory
            ->createNotification('Some message', 'success', Argument::any())
            ->shouldBeCalled()
            ->willReturn($notification);
        $factory->createUserNotification($notification, Argument::type('Oro\Bundle\UserBundle\Entity\User'))
            ->shouldBeCalledTimes(2)
            ->willReturn($userNotification);

        $em->persist($notification)->shouldBeCalled();
        $em->persist(Argument::type('Pim\Bundle\NotificationBundle\Entity\UserNotification'))->shouldBeCalledTimes(2);
        $em->flush()->shouldBeCalled();

        $this->notify([$user, $user2], 'Some message')->shouldReturn($this);
    }

    function it_can_return_all_notifications_for_a_user(UserNotification $userNotification, User $user, $repository)
    {
        $repository->findBy(['user' => $user], ['id' => 'DESC'], 10, 15)->willReturn([$userNotification]);
        $this->getUserNotifications($user, 15)->shouldReturn([$userNotification]);
    }

    function it_marks_a_notification_as_viewed(UserNotification $userNotification, User $user, $repository, $em)
    {
        $notificationId = '1';
        $repository->findBy(['user' => $user, 'id' => $notificationId])->shouldBeCalled()->willReturn([$userNotification]);
        $userNotification->setViewed(true)->shouldBeCalled()->willReturn($userNotification);

        $em->persist($userNotification)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $this->markAsViewed($user, $notificationId);
    }

    function it_marks_all_notifications_as_viewed(
        UserNotification $userNotification1,
        UserNotification $userNotification2,
        User $user,
        $repository,
        $em
    ) {
        $repository
            ->findBy(['user' => $user, 'viewed' => false])
            ->shouldBeCalled()
            ->willReturn([$userNotification1, $userNotification2]);

        $userNotification1->setViewed(true)->shouldBeCalled()->willReturn($userNotification1);
        $userNotification2->setViewed(true)->shouldBeCalled()->willReturn($userNotification2);

        $em->persist($userNotification1)->shouldBeCalled();
        $em->persist($userNotification2)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $this->markAsViewed($user, 'all');
    }

    function it_counts_unread_user_notifications_for_a_user(User $user, $repository)
    {
        $repository->countUnreadForUser($user)->shouldBeCalled()->willReturn(3);

        $this->countUnreadForUser($user)->shouldReturn(3);
    }
}
