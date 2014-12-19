<?php
/**************************************
 *
 * Author: Piotr Sroczkowski
 *
**************************************/

namespace Mondo\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {
    public function indexAction($name) {
        return $this->render('MondoBookingBundle:Default:index.html.twig', array('name' => $name));
    }
}
