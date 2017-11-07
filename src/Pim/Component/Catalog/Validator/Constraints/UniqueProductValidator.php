<?php

namespace Pim\Component\Catalog\Validator\Constraints;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

/**
 * @author    Marie Bochu <marie.bochu@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class UniqueProductValidator extends ConstraintValidator
{
    /** @var ObjectManager */
    private $objectManager;

    /**
     * @param ObjectManager $objectManager
     *
     * @internal param IdentifiableObjectRepositoryInterface $productRepository
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function validate($product, Constraint $constraint)
    {
        if (null === $product) {
            return;
        }

        $repository = $this->objectManager->getRepository($constraint->entityClass);
        $supportedClass = $repository->getClassName();

        if (!$product instanceof $supportedClass) {
            throw new ConstraintDefinitionException(sprintf('The "%s" entity repository does not support the "%s" entity. The entity should be an instance of or extend "%s".', $constraint->entityClass, $class->getName(), $supportedClass));
        }

        $getter = 'get' . ucfirst($constraint->identifier);
        $data = $repository->findOneBy([$constraint->identifier => $product->$getter()]);
        if (null === $data) {
            return;
        }

        if ($product->getId() !== $data->getId()) {
            $this->context->buildViolation($constraint->message)
                ->atPath($constraint->identifier)
                ->addViolation();
        }
    }
}
