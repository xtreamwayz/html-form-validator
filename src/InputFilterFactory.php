<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

namespace Xtreamwayz\HTMLFormValidator;

use Psr\Container\ContainerInterface;
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
    public function __invoke(ContainerInterface $container) : Factory
    {
        $config     = $container->get('config')['zend-inputfilter'] ?? [];
        $filters    = $config['filters'] ?? [];
        $validators = $config['validators'] ?? [];

        // Construct factory
        $factory = new Factory(new InputFilterPluginManager($container));
        $factory
            ->getDefaultFilterChain()
            ->setPluginManager(new FilterPluginManager($container, $filters));
        $factory
            ->getDefaultValidatorChain()
            ->setPluginManager(new ValidatorPluginManager($container, $validators));

        return $factory;
    }
}
