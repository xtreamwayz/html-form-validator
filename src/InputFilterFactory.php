<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

declare(strict_types = 1);

namespace Xtreamwayz\HTMLFormValidator;

use Interop\Container\ContainerInterface;
use Zend\Filter\FilterPluginManager;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\Validator\ValidatorPluginManager;

class InputFilterFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return Factory
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        $filters = $config['zend-inputfilter']['filters'] ?? [];
        $validators = $config['zend-inputfilter']['validators'] ?? [];

        // Construct factory
        $factory = new Factory(new InputFilterPluginManager($container));
        $factory->getDefaultFilterChain()
            ->setPluginManager(new FilterPluginManager($container, $filters));
        $factory->getDefaultValidatorChain()
            ->setPluginManager(new ValidatorPluginManager($container, $validators));

        return $factory;
    }
}
