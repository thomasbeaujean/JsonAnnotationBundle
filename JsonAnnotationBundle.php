<?php

namespace thomasbeaujean\JsonAnnotationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use tbn\Bundle\JsonAnnotationBundle\DependencyInjection\Compiler\AddParamConverterPass;


/**
 * SensioFrameworkExtraBundle.
 *
 * @author     Thomas Beaujean
 */
class JsonAnnotationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}
