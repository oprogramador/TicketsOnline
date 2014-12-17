<?php

namespace Mondo\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use Mondo\BookingBundle\Entity\CustomerValidator;
use Mondo\AppBundle\Translator\MyTranslator;

/**
 * Customer
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Customer
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1)
     */
    private $gender;

    /**
     * @var integer
     *
     * @ORM\Column(name="childs", type="smallint")
     */
    private $childs;

    /**
     * @var integer
     *
     * @ORM\Column(name="adults", type="smallint")
     */
    private $adults;

    /**
     * @var integer
     *
     * @ORM\Column(name="seniors", type="smallint")
     */
    private $seniors;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Customer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Customer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Customer
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return Customer
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set childs
     *
     * @param integer $childs
     * @return Customer
     */
    public function setChilds($childs)
    {
        $this->childs = $childs;

        return $this;
    }

    /**
     * Get childs
     *
     * @return integer 
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * Set adults
     *
     * @param integer $adults
     * @return Customer
     */
    public function setAdults($adults)
    {
        $this->adults = $adults;

        return $this;
    }

    /**
     * Get adults
     *
     * @return integer 
     */
    public function getAdults()
    {
        return $this->adults;
    }

    /**
     * Set seniors
     *
     * @param integer $seniors
     * @return Customer
     */
    public function setSeniors($seniors)
    {
        $this->seniors = $seniors;

        return $this;
    }

    /**
     * Get seniors
     *
     * @return integer 
     */
    public function getSeniors()
    {
        return $this->seniors;
    }

    public function getTotalTickets() {
        return $this->childs +  $this->adults + $this->seniors;
    }

    public static function validateChilds($object, ExecutionContextInterface $context) {
        if( $object->getChilds() > 4 )
            $context->addViolationAt('childs', MyTranslator::trans('booking', 'customer.validation.childs'), array(), [null]);
    }

    public static function validateAdults($object, ExecutionContextInterface $context) {
        if( $object->getAdults() > 4 )
            $context->addViolationAt('adults', MyTranslator::trans('booking', 'customer.validation.adults'), array(), [null]);
    }

    public static function validateSeniors($object, ExecutionContextInterface $context) {
        if( $object->getSeniors() > 3 )
            $context->addViolationAt('seniors', MyTranslator::trans('booking', 'customer.validation.seniors'), array(), [null]);
    }

    public static function validateMin($object, ExecutionContextInterface $context) {
        if( $object->getTotalTickets() < 1 )
            $context->addViolationAt('adults', MyTranslator::trans('booking', 'customer.validation.min'), array(), [null]);
    }

    public static function validateMax($object, ExecutionContextInterface $context) {
        if( $object->getTotalTickets() > 6 )
            $context->addViolationAt('adults', MyTranslator::trans('booking', 'customer.validation.max'), array(), [null]);
    }

    public static function validateAdultChild($object, ExecutionContextInterface $context) {
        if( $object->getAdults()==0 ||  $object->getChilds() / $object->getAdults() > 4 )
            $context->addViolationAt('adults', MyTranslator::trans('booking', 'customer.validation.adult_child'), array(), [null]);
    }

    public static function validateGender($object, ExecutionContextInterface $context) {
        if( !in_array($object->getGender(), ['m', 'f'] ))
            $context->addViolationAt('gender', MyTranslator::trans('booking', 'customer.validation.gender'), array(), [null]);
    }


    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new Assert\Callback(array(
            'validateChilds',
            'validateAdults',
            'validateSeniors',
            'validateMin',
            'validateMax',
            'validateAdultChild',
            'validateGender'
        )));
    }
}
