<?php

/*
 * Copyright (c) 2011-2015 Lp digital system
 *
 * This file is part of BackBee.
 *
 * BackBee is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * BackBee is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with BackBee. If not, see <http://www.gnu.org/licenses/>.
 */

namespace BackBuilder\Bundle\ToolbarBundle\Controller;

use BackBuilder\Bundle\ToolbarBundle\Plugin\PluginManager;
use BackBuilder\Config\Config;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * ToolbarBundle config API allows to get toolbar, plugin, rte, etc. configurations
 *
 * @category  BackBuilder
 * @package   BackBuilder\Bundle\ToolbarBundle
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
    private $plugin_manager;

    /**
     * ConfigController's constructor
     *
     * @param Config        $config
     * @param PluginManager $manager
     */
    public function __construct(Config $config, PluginManager $manager)
    {
        $this->config = $config;
        $this->plugin_manager = $manager;
    }

    /**
     * Getters of toolbar complete config
     *
     * @return JsonResponse
     */
    public function getAction()
    {
        return new JsonResponse($this->plugin_manager->getPluginConfiguration());
    }
}