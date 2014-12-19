<?php
namespace Mondo\CustomerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Mondo\BookingBundle\Entity\TicketInfo;

class LoadTicketInfoData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $loader = new \Nelmio\Alice\Loader\Yaml();
        $objects = $loader->load(__DIR__.DIRECTORY_SEPARATOR.'ticket_info.yml');

        $persister = new \Nelmio\Alice\ORM\Doctrine($manager);
        $persister->persist($objects);
    }

    public function getOrder() {
        return 1;
    }
}

