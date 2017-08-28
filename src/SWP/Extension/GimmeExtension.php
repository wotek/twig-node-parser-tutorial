<?php

namespace SWP\Extension;

use SWP\Parser\GimmeTokenParser;

class GimmeExtension extends \Twig_Extension
{
    /**
     * Simple data loader implementation.
     */
    public function getLoader()
    {
        return new class() {
            public function load($metaType, $parameters = [])
            {
                return [
                    'metaType' => $metaType,
                    'parameters' => $parameters,
                ];
            }
        };
    }

    /**
     * @return array
     */
    public function getTokenParsers()
    {
        return [new GimmeTokenParser()];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::class;
    }
}
