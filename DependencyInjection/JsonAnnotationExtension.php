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


        foreach ($annotationsToLoad as $config) {
            $loader->load($config);
        }
    }
}
