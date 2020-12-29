<?php

declare(strict_types=1);

namespace XtreamwayzTest\HTMLFormValidator;

use Laminas\InputFilter\Factory;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ProphecyInterface;
use Psr\Container\ContainerInterface;
use Xtreamwayz\HTMLFormValidator\FormFactoryFactory;
use Xtreamwayz\HTMLFormValidator\FormFactoryInterface;

class FormFactoryFactoryTest extends TestCase
{
    use ProphecyTrait;

    /** @var ContainerInterface|ProphecyInterface */
    private $container;

    /** @var Factory|ProphecyInterface */
    private $inputFilterFactory;

    /** @var FormFactoryFactory */
    private $formFactoryFactory;

    protected function setUp(): void
    {
        $this->container          = $this->prophesize(ContainerInterface::class);
        $this->inputFilterFactory = $this->prophesize(Factory::class);
        $this->formFactoryFactory = new FormFactoryFactory();
    }

    public function testEmptyWithEmptyContainer(): void
    {
        $this->container->has(Factory::class)->willReturn(false);
        $this->container->get('config')->willReturn([]);

        $formFactory = ($this->formFactoryFactory)($this->container->reveal());

        self::assertInstanceOf(FormFactoryInterface::class, $formFactory);
    }

    public function testContainerWithFactory(): void
    {
        $this->container->has(Factory::class)->willReturn(true);
        $this->container->get(Factory::class)->will([$this->inputFilterFactory, 'reveal'])->shouldBeCalled();
        $this->container->get('config')->willReturn([]);

        $formFactory = ($this->formFactoryFactory)($this->container->reveal());

        self::assertInstanceOf(FormFactoryInterface::class, $formFactory);
    }
}
