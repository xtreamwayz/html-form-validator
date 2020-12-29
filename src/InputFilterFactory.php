<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator;

use Laminas\Filter\FilterPluginManager;
use Laminas\InputFilter\Factory;
use Laminas\InputFilter\InputFilterPluginManager;
use Laminas\Validator\ValidatorPluginManager;
use Psr\Container\ContainerInterface;

class InputFilterFactory
{
    public function __invoke(ContainerInterface $container): Factory
    {
        $config     = $container->get('config')['laminas-inputfilter'] ?? [];
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
