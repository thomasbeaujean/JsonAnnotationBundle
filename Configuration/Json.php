<?php

namespace tbn\JsonAnnotationBundle\Configuration;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * The Template class handles the @Json annotation parts.
 *
 * @author Thomas Beaujean <thomas@appventus.com>
 * @Annotation
 */
class Json extends ConfigurationAnnotation
{
     /**
     * Returns the annotation alias name.
     *
     * @return string
     * @see ConfigurationInterface
     */
    public function getAliasName()
    {
        return 'json';
    }

    /**
     * Only one template directive is allowed
     *
     * @return Boolean
     * @see ConfigurationInterface
     */
    public function allowArray()
    {
        return false;
    }
}
