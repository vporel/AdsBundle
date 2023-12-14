<?php
namespace AdsBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * Advertisements bundle
 * @author Vivian NKOUANANG (https://github.com/vporel) <dev.vporel@gmail.com>
 */
class AdsBundle extends AbstractBundle{

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
        ->children()
            
        ->end();
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import(dirname(__DIR__)."/config/services.yaml");
        $container->parameters()->set("ads", $config);
    }
    
}