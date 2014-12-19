<?php

namespace Mondo\BookingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mondo\BookingBundle\Entity\Customer;
use Mondo\BookingBundle\Form\CustomerType;
use Mondo\AppBundle\Translator\MyTranslator;
use Mondo\AppBundle\Translator\ITranslateable;

use Mondo\AppBundle\Mailer\Mailer;
use Mondo\AppBundle\Util\Util;

/**
 * Customer controller.
 *
 */
class CustomerController extends Controller implements ITranslateable 
{

    public static $instance;

    public function __construct() {
        MyTranslator::getInstance()->addTranslator('booking', $this);
        self::$instance = $this;
    }


    public function trans($msg) {
        return $this->get('translator')->trans($msg);
    }

    public function getValidator() {
        return $this->get('validator');
    }

    public function getTranslator() {
        return $this->get('translator');
    }

    public function getDoctrineManager() {
        return $this->getDoctrine()->getManager();
    }

    private function sendMail($to, $vernr) {
        try {
            $message = \Swift_Message::newInstance()
                ->setSubject('Ticket booking confirmation')
                ->setFrom('send@example.com')
                ->setTo($to)
                ->setBody(
                    $this->renderView(
                        'MondoBookingBundle:Mail:email.txt.twig',
                        array('vernr' => $vernr)
                    )
                )
                ;
            $this->get('mailer')->send($message);
        } catch(\Exception $e) {
            throw $e;
        }
    }

    public function mailAndVerif($entity) {
        $entity->setVerified(false);
        $entity->setVernr(Util::randStrAlpha(8));
        try {
            $this->sendMail($entity->getEmail(), $entity->getVernr());
        } catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new Customer entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Customer();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('customer_show', array('vernr' => $entity->getVernr())));
        }

        return $this->renderCreateForm($entity, $form);
    }

    private function renderCreateForm($entity, $form) {
        return $this->render('MondoBookingBundle:Customer:new.html.twig', array(
            'childPrice' => $this->getTypePrice('child'),
            'adultPrice' => $this->getTypePrice('adult'),
            'seniorPrice' => $this->getTypePrice('senior'),
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Customer entity.
     *
     * @param Customer $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Customer $entity)
    {
        $form = $this->createForm(new CustomerType(), $entity, array(
            'action' => $this->generateUrl('customer_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => $this->get('translator')->trans('create')));

        return $form;
    }

    /**
     * Displays a form to create a new Customer entity.
     *
     */
    public function newAction()
    {
        $entity = new Customer();
        $form   = $this->createCreateForm($entity);

        return $this->renderCreateForm($entity, $form);
    }

    /**
     * Finds and displays a Customer entity.
     *
     */
    public function showAction($vernr)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->createQuery('SELECT x FROM Mondo\BookingBundle\Entity\Customer x WHERE x.vernr=:nr')
            ->setParameter('nr', $vernr)
            ->getSingleResult();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        return $this->render('MondoBookingBundle:Customer:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    public function verifyAction(Request $request, $vernr)
    {
        $em = $this->getDoctrine()->getManager();
        $em->createQuery('UPDATE Mondo\BookingBundle\Entity\Customer x SET x.verified=1 WHERE x.vernr=:nr')
            ->setParameter('nr', $vernr)
            ->getResult()
            ;
        $em->flush();

        return $this->redirect($this->generateUrl('customer_show', array('vernr' => $vernr)));
    }

    public function getTypePrice($typeName) {
        $em = CustomerController::$instance->getDoctrineManager();
        return $em->createQuery('SELECT x FROM Mondo\BookingBundle\Entity\TicketInfo x WHERE x.type=:type')
            ->setParameter('type', $typeName)
            ->getSingleResult()
            ->getPrice();
    }
}
