<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class OpenClassroomsOneSkyExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/'));
        $loader->load('services.xml');
        $config = $this->processConfiguration(new Configuration(), $configs);
        $this->processParameters($container, $config);
    }

    private function processParameters(ContainerBuilder $container, array $config)
    {
        $container->setParameter('openclassrooms_onesky.api_key', $config['api_key']);
        $container->setParameter('openclassrooms_onesky.api_secret', $config['api_secret']);
        $container->setParameter('openclassrooms_onesky.projects', $config['projects']);
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return 'openclassrooms_onesky';
    }
}
