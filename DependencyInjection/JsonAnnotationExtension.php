<?php

namespace thomasbeaujean\JsonAnnotationBundle\DependencyInjection;

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
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $annotationsToLoad = array();


        $annotationsToLoad[] = 'view.xml';

	    $this->addClassesToCompile(array(
	  	    'thomasbeaujean\\JsonAnnotationBundle\\EventListener\\JsonListener',
        ));

        $loader->load('annotations.xml');

        foreach ($annotationsToLoad as $config) {
            $loader->load($config);
         }
    }

    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }

    public function getNamespace()
    {
        return 'http://symfony.com/schema/dic/symfony_extra';
    }
}
