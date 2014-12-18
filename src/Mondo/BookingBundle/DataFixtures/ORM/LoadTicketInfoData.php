<?php
namespace Mondo\CustomerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Mondo\CustomerBundle\Entity\Contractor;

class LoadCustomerData extends AbstractFixture implements OrderedFixtureInterface {
	public function load(ObjectManager $manager) {
		$manager->flush();
	}

	public function getOrder() {
		return 1;
	}
}

