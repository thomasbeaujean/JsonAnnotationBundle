<?php

namespace tbn\JsonAnnotationBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

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
            array("%tbn.json_annotation.view.listener.class%")
        );

        //the listener is added only if required
        if ($config['enable_authentication_error']) {
            $this->addAjaxAuthenticationErrorListener($container);
        }
    }

    /**
     *
     * @param ContainerBuilder $container
     */
    protected function addAjaxAuthenticationErrorListener(ContainerBuilder $container)
    {
        $container->setParameter('tbn.json_annotation.ajax_authentication.listener.class', 'tbn\\JsonAnnotationBundle\\EventListener\\AjaxAuthenticationListener');
        $translatorDefinition = new Reference('translator');
        $service = new Definition('%tbn.json_annotation.ajax_authentication.listener.class%', [$translatorDefinition]);
        $service->addTag('kernel.event_listener', ['event' => 'kernel.exception', 'method' => 'onCoreException', 'priority' => 1000 ]);
        $container->setDefinition('tbn.json_annotation.ajax_authentication.listener', $service);
    }
}
