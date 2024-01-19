<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfServiceContainerLoaderArray loads an array service definitions.
 *
 * It does not include import functionnality.
 *
 * @author     Jerome Macias <jmacias@groupe-exp.com>
 */
class sfServiceContainerLoaderArray extends \sfServiceContainerLoader
{
    public function doLoad($content)
    {
        $this->validate($content);

        $parameters = [];
        $definitions = [];

        // parameters
        if (isset($content['parameters'])) {
            foreach ($content['parameters'] as $key => $value) {
                $parameters[strtolower($key)] = $this->resolveServices($value);
            }
        }

        // services
        if (isset($content['services'])) {
            foreach ($content['services'] as $id => $service) {
                $definitions[$id] = $this->parseDefinition($service);
            }
        }

        return [$definitions, $parameters];
    }

    protected function validate($content)
    {
        if (!is_array($content)) {
            throw new \InvalidArgumentException('The service definition is not valid.');
        }

        foreach (array_keys($content) as $key) {
            if (!in_array($key, ['parameters', 'services'])) {
                throw new \InvalidArgumentException(sprintf('The service defintion is not valid ("%s" is not recognized).', $key));
            }
        }

        return $content;
    }

    protected function parseDefinition($service)
    {
        if (is_string($service) &&   str_starts_with($service, '@')) {
            return substr($service, 1);
        }

        $definition = new \sfServiceDefinition($service['class']);

        if (isset($service['shared'])) {
            $definition->setShared($service['shared']);
        }

        if (isset($service['constructor'])) {
            $definition->setConstructor($service['constructor']);
        }

        if (isset($service['file'])) {
            $definition->setFile($service['file']);
        }

        if (isset($service['arguments'])) {
            $definition->setArguments($this->resolveServices($service['arguments']));
        }

        if (isset($service['configurator'])) {
            if (is_string($service['configurator'])) {
                $definition->setConfigurator($service['configurator']);
            } else {
                $definition->setConfigurator([$this->resolveServices($service['configurator'][0]), $service['configurator'][1]]);
            }
        }

        if (isset($service['calls'])) {
            foreach ($service['calls'] as $call) {
                $definition->addMethodCall($call[0], $this->resolveServices($call[1]));
            }
        }

        return $definition;
    }

    protected function resolveServices($value)
    {
        if (is_array($value)) {
            $value = array_map([$this, 'resolveServices'], $value);
        } elseif (is_string($value) &&   str_starts_with($value, '@')) {
            $value = new \sfServiceReference(substr($value, 1));
        }

        return $value;
    }
}
