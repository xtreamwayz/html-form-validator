<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator;

use Laminas\InputFilter\Factory;
use Psr\Container\ContainerInterface;

class FormFactoryFactory
{
    public function __invoke(ContainerInterface $container): FormFactoryInterface
    {
        $factory = null;
        if ($container->has(Factory::class)) {
            $factory = $container->get(Factory::class);
        }

        $options = $container->get('config')['html-form-validator'] ?? [];

        return new FormFactory($factory, $options);
    }
}
