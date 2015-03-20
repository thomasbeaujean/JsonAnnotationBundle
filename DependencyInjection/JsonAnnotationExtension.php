<?php

namespace tbn\JsonAnnotationBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;


/**
 * SensioFrameworkExtraExtension.
 *
 * @author Thomas Beaujean
 */
class JsonAnnotationExtension extends Extension
{
    /**
     * Load the eventListener
     *
     * @param array            $configs   The config
     * @param ContainerBuilder $container The container
     *
     * @return nothing
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        //set all config as parameter
        foreach ($config as $key => $value) {
            $container->setParameter('tbn.json_annotation.'.$key, $value);
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        //for performance, compile the listener class
	    $this->addClassesToCompile(
	        array(
	            "%tbn.json_annotation.view.listener.class%"
            )
	    );
    }
}
