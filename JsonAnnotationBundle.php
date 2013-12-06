<?php

namespace thomasbeaujean\JsonAnnotationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * JsonAnnotationBundle.
 *
 * @author Thomas Beaujean
 */
class JsonAnnotationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}
