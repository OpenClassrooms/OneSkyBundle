<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Framework\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ScopeInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ContainerForTest implements ContainerInterface
{
    /**
     * @var string[]
     */
    public static $parameters;

    /**
     * @var object[]
     */
    public static $services;

    public function __construct(array $parameters = [], array $services = [])
    {
        self::$parameters = $parameters;
        self::$services = $services;
    }

    /**
     * {@inheritdoc}
     */
    public function set($id, $service, $scope = self::SCOPE_CONTAINER)
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id, $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE)
    {
        return self::$services[$id];
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function initialized($id)
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($name)
    {
        return self::$parameters[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameter($name)
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter($name, $value)
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function enterScope($name)
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function leaveScope($name)
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function addScope(ScopeInterface $scope)
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function hasScope($name)
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function isScopeActive($name)
    {
        return;
    }
}

