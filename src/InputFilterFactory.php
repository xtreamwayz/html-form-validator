<?php

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
        $filters = isset($config['zend-inputfilter']['filters']) ? $config['zend-inputfilter']['filters'] : [];
        $validators = isset($config['zend-inputfilter']['validators']) ? $config['zend-inputfilter']['validators'] : [];

        // Construct factory
        $factory = new Factory(new InputFilterPluginManager($container));
        $factory->getDefaultFilterChain()
                ->setPluginManager(new FilterPluginManager($container, $filters));
        $factory->getDefaultValidatorChain()
                ->setPluginManager(new ValidatorPluginManager($container, $validators));

        return $factory;
    }
}
