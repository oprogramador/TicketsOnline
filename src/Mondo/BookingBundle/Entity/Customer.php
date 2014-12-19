<?php

namespace Mondo\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use Mondo\BookingBundle\Controller\CustomerController;
use Mondo\AppBundle\Translator\MyTranslator;
use Symfony\Component\Validator\Constraints\NotBlank;

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
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=true)
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
     * @var boolean
     *
     * @ORM\Column(name="verified", type="boolean")
     */
    private $verified;

    /**
     * @var string
     *
     * @ORM\Column(name="vernr", type="string", length=8)
     */
    private $vernr;


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
     * Get full name off gender using translations
     *
     * @return string 
     */
    public function getGenderLong()
    {
        if(is_null($this->gender)) return null;
        return MyTranslator::trans('booking', 'customer.values.gender.'.$this->gender);
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

    public static function validateEmail($object, ExecutionContextInterface $context) {
        try {
            CustomerController::$instance->mailAndVerif($object);
        } catch(\Exception $e) {
            $context->addViolationAt('email', MyTranslator::trans('booking', 'customer.validation.email'), array(), array(null));
        }
    }

    public static function validateChilds($object, ExecutionContextInterface $context) {
        if( $object->getChilds() > 4 )
            $context->addViolationAt('childs', MyTranslator::trans('booking', 'customer.validation.childs'), array(), array(null));
    }

    public static function validateAdults($object, ExecutionContextInterface $context) {
        if( $object->getAdults() > 4 )
            $context->addViolationAt('adults', MyTranslator::trans('booking', 'customer.validation.adults'), array(), array(null));
    }

    public static function validateSeniors($object, ExecutionContextInterface $context) {
        if( $object->getSeniors() > 3 )
            $context->addViolationAt('seniors', MyTranslator::trans('booking', 'customer.validation.seniors'), array(), array(null));
    }

    public static function validateMin($object, ExecutionContextInterface $context) {
        if( $object->getTotalTickets() < 1 )
            $context->addViolationAt('adults', MyTranslator::trans('booking', 'customer.validation.min'), array(), array(null));
    }

    public static function validateMax($object, ExecutionContextInterface $context) {
        if( $object->getTotalTickets() > 6 )
            $context->addViolationAt('adults', MyTranslator::trans('booking', 'customer.validation.max'), array(), array(null));
    }

    public static function validateAdultChild($object, ExecutionContextInterface $context) {
        if( $object->getAdults()==0 ||  $object->getChilds() / $object->getAdults() > 4 )
            $context->addViolationAt('adults', MyTranslator::trans('booking', 'customer.validation.adult_child'), array(), array(null));
    }

    public static function validateGender($object, ExecutionContextInterface $context) {
        if( !in_array($object->getGender(), array('m', 'f') ))
            $context->addViolationAt('gender', MyTranslator::trans('booking', 'customer.validation.gender'), array(), array(null));
    }


    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $ar = array('name', 'email', 'childs', 'adults', 'seniors');
        foreach($ar as $i) 
            $metadata->addPropertyConstraint($i, new NotBlank(array(
                'message' => MyTranslator::trans('booking', 'customer.validation.not_blank'),
            ))); 
        $metadata->addConstraint(new Assert\Callback(array(
            'validateEmail',
            'validateChilds',
            'validateAdults',
            'validateSeniors',
            'validateMin',
            'validateMax',
            'validateAdultChild',
            //'validateGender'
        )));
    }

    /**
     * Set verified
     *
     * @param boolean $verified
     * @return Customer
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * Get verified
     *
     * @return boolean 
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * Set vernr
     *
     * @param string $vernr
     * @return Customer
     */
    public function setVernr($vernr)
    {
        $this->vernr = $vernr;

        return $this;
    }

    /**
     * Get vernr
     *
     * @return string 
     */
    public function getVernr()
    {
        return $this->vernr;
    }

    public function getTotalPrice() {
        return number_format($this->getTotalPriceNum(), 2, '.', ' ');
    }

    public function getTotalPriceNum() {
        $childPrice = CustomerController::$instance->getTypePrice('child');
        $adultPrice = CustomerController::$instance->getTypePrice('adult');
        $seniorPrice = CustomerController::$instance->getTypePrice('senior');
        return $this->childs * $childPrice + $this->adults * $adultPrice + $this->seniors * $seniorPrice;
    }
}
