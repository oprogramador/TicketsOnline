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


    private function sendMail($to, $vernr) {
        file_put_contents('/home/pierre/log.txt', "\n\nmail", FILE_APPEND);
        try {
            file_put_contents('/home/pierre/log.txt', "\n\ntry", FILE_APPEND);
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
            file_put_contents('/home/pierre/log.txt', "\n\ncatch", FILE_APPEND);
            return $this->redirect($this->generateUrl('customer_new'));
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
            $entity->setVerified(false);
            $entity->setVernr(Util::randStrAlpha(8));
            $em->persist($entity);
            $em->flush();

            $this->sendMail($entity->getEmail(), $entity->getVernr());
            return $this->redirect($this->generateUrl('customer_show', array('vernr' => $entity->getVernr())));
        }

        return $this->render('MondoBookingBundle:Customer:new.html.twig', array(
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
        echo __DIR__;
        $entity = new Customer();
        $form   = $this->createCreateForm($entity);

        return $this->render('MondoBookingBundle:Customer:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
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

        return $this->redirect($this->generateUrl('customer'));
    }
}
