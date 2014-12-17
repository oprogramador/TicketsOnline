<?php

namespace Mondo\CustomerBundle\Controller;

use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use InternetowaKsiegowosc\ApiBundle\Translator\MyTranslator;

class CustomerValidator {

	public static function validateChilds($object, ExecutionContextInterface $context) {
            if( $object->getChilds() > 4 )
                $context->addViolationAt('childs', "MyTranslator::trans('contractor', 'contractor.validation.nip.control'), array()", null);
	}
}

