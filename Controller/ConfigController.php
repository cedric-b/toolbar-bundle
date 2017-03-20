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

namespace BackBee\Bundle\ToolbarBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

use BackBee\Bundle\ToolbarBundle\Plugin\PluginManager;
use BackBee\Config\Config;
use BackBee\Routing\RouteCollection;

/**
 * ToolbarBundle config API allows to get toolbar, plugin, rte, etc. configurations
 *
 * @category  BackBee
 * @package   BackBee\Bundle\ToolbarBundle
 * @copyright Lp digital system
 * @author    e.chau <eric.chau@lp-digital.fr>
 */
class ConfigController
{
    /**
     * This is the ToolbarBundle config
     * @var Config
     */
    private $config;

    /**
     * @var PluginManager
     */
    private $pluginManager;

    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * ConfigController's constructor
     *
     * @param Config          $config
     * @param PluginManager   $manager
     * @param RouteCollection $routeCollection
     */
    public function __construct(Config $config, PluginManager $manager, RouteCollection $routeCollection)
    {
        $this->config = $config;
        $this->pluginManager = $manager;
        $this->routeCollection = $routeCollection;
    }

    /**
     * Getters of toolbar complete config
     *
     * @return JsonResponse
     */
    public function getAction()
    {
        $settings = $this->config->getSettingsConfig();
        $corePlugins = $this->pluginManager->getPluginConfiguration();

        $config = array_merge($settings ?: [], $corePlugins);

        return new JsonResponse($this->resolveResourceBaseUrl($config));
    }

    /**
     * Resolves %resources-baseurl% in settings.
     *
     * @param  array $settings
     *
     * @return array
     */
    private function resolveResourceBaseUrl(array $settings)
    {
        array_walk_recursive(
            $settings,
            function (&$item, $key, RouteCollection $routeCollection) {
                if (0 === strpos($item, '$resources-baseurl$')) {
                    $item = $routeCollection->getUri(str_replace('$resources-baseurl$', '', $item), '/', null, RouteCollection::RESOURCE_URL);
                }
            },
            $this->routeCollection
        );

        return $settings;
    }
}
