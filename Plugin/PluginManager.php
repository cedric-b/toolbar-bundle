<?php

/*
 * Copyright (c) 2011-2017 Lp digital system
 *
 * This file is part of toolbar-bundle.
 *
 * toolbar-bundle is free bundle: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * toolbar-bundle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with toolbar-bundle. If not, see <http://www.gnu.org/licenses/>.
 */

namespace BackBee\Bundle\ToolbarBundle\Plugin;

use BackBee\DependencyInjection\ContainerInterface;

/**
 * PluginManager allows to handle toolbar plugin with ease
 *
 * @category  BackBee
 * @package   BackBee\Bundle\ToolbarBundle
 * @copyright Lp digital system
 * @author    e.chau <eric.chau@lp-digital.fr>
 */
class PluginManager
{
    /**
     * constant that define the tag name to use for toolbar plugin
     */
    const PLUGIN_SERVICE_TAG = 'toolbar.plugin';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * PluginManager's constructor
     *
     * @param ContainerInterface $container service container in where we can find every plugins services
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Getters of every toolbar's plugins
     * Note that plugin must be registered in application's service container with 'toolbar.plugin' tag
     * to be recognized by PluginManager as toolbar plugin.
     *
     * @return array
     */
    public function getPlugins()
    {
        $plugins = [];
        foreach (array_keys($this->container->findTaggedServiceIds(self::PLUGIN_SERVICE_TAG)) as $service_id) {
            $plugin = $this->container->get($service_id);
            if ($this->isValidPlugin(($plugin))) {
                $plugins[] = $plugin;
            }
        }

        return $plugins;
    }

    /**
     * Computes and returns every toolbar plugins configurations
     *
     * @return array
     */
    public function getPluginConfiguration()
    {
        $toolbarPluginConfig = $this->container->get('bundle.toolbar.config')->getPluginsConfig();
        $config = [
            'plugins' => array_merge([
                'namespace' => []
            ], $toolbarPluginConfig ?: []),
        ];

        foreach ($this->getPlugins() as $plugin) {
            $config['plugins']['namespace'][$plugin->getName()] = $plugin->getNamespace();
            $config['plugins'][$plugin->getName()] = $plugin->getConfig();
        }

        return $config;
    }

    /**
     * Checks if provided plugins is valid by testing if it implements PluginInterface
     *
     * @param  object  $plugin the plugin we want to validate
     * @return boolean return true if provided plugin is valid
     * @throws InvalidArgumentException raises if provided plugin does not implement PluginInterface
     */
    private function isValidPlugin($plugin)
    {
        if (!($plugin instanceof PluginInterface)) {
            throw new \InvalidArgumentException(
                '`' . get_class($plugin) . '` is tagged as `' . self::PLUGIN_SERVICE_TAG .
                '` but it does not implement `' . __NAMESPACE__ . '\PluginInterface`'
            );
        }

        return true;
    }
}
