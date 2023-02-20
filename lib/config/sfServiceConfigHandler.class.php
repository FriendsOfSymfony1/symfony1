<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfSecurityConfigHandler allows you to configure action security.
 *
 * @author     Jerome Tamarelle <jtamarelle@groupe-exp.com>
 *
 * @version    SVN: $Id$
 */
class sfServiceConfigHandler extends sfYamlConfigHandler
{
    /**
     * Executes this configuration handler.
     *
     * @param array $configFiles An array of absolute filesystem path to a configuration file
     *
     * @return string Data to be written to a cache file
     */
    public function execute($configFiles)
    {
        $class = sfConfig::get('sf_app').'_'.sfConfig::get('sf_environment').'ServiceContainer';

        $serviceContainerBuilder = new sfServiceContainerBuilder();

        $loader = new sfServiceContainerLoaderArray($serviceContainerBuilder);
        $loader->load(static::getConfiguration($configFiles));

        $dumper = new sfServiceContainerDumperPhp($serviceContainerBuilder);
        $code = $dumper->dump(array(
            'class' => $class,
            'base_class' => $this->parameterHolder->get('base_class'),
        ));

        // compile data
        $retval = sprintf(
            "<?php\n".
            "// auto-generated by sfServiceConfigHandler\n".
            "// date: %s\n\n".
            "\$class = '%s';\n".
            "if (!class_exists(\$class, false)) {\n".
            "%s\n".
            "}\n".
            "return \$class;\n\n",
            date('Y/m/d H:i:s'),
            $class,
            $code
        );

        return $retval;
    }

    /**
     * @see sfConfigHandler
     * {@inheritdoc}
     */
    public static function getConfiguration(array $configFiles)
    {
        $config = static::parseYamls($configFiles);
        $config = static::flattenConfigurationWithEnvironment($config);

        return $config;
    }
}
