<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator;

use Psr\Container\ContainerInterface;
use Zend\Filter\FilterPluginManager;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\Validator\ValidatorPluginManager;

class InputFilterFactory
{
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
