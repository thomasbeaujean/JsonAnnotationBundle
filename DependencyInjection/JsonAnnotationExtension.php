<?php

namespace tbn\JsonAnnotationBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
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

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $annotationsToLoad = array();
        $annotationsToLoad[] = 'view.xml';

	    $this->addClassesToCompile(
	        array(
	  	        'tbn\\JsonAnnotationBundle\\EventListener\\JsonListener',
            )
	    );

        foreach ($annotationsToLoad as $config) {
            $loader->load($config);
        }
    }
}
