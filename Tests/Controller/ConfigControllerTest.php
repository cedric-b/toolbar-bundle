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

namespace BackBee\Bundle\ToolbarBundle\Tests\Controller;

use Symfony\Component\DependencyInjection\Definition;

use BackBee\Bundle\ToolbarBundle\Controller\ConfigController;
use BackBee\Bundle\ToolbarBundle\Plugin\PluginManager;
use BackBee\Config\Config;
use BackBee\DependencyInjection\Container;
use BackBee\Routing\RouteCollection;

/**
 * ConfigController tests
 *
 * @author e.chau <eric.chau@lp-digital.fr>
 *
 * @coversDefaultClass \BackBee\Bundle\ToolbarBundle\Controller\ConfigController
 */
class ConfigControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConfigController
     */
    private $controller;

    /**
     * @var PluginManager
     */
    private $plugin_manager;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $container = new Container();
        $config = new Config(__DIR__);
        $route = new RouteCollection();

        $container->set('bundle.toolbar.config', $config);
        $definition = new Definition('BackBee\Bundle\ToolbarBundle\Tests\Plugin\TestPlugin');;
        $container->setDefinition('test_plugin', $definition->addTag(PluginManager::PLUGIN_SERVICE_TAG));

        $this->plugin_manager = new PluginManager($container);
        $this->controller = new ConfigController($config, $this->plugin_manager, $route);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->controller = null;
        $this->plugin_manager = null;
    }

    public function testGetAction()
    {
        $response = $this->controller->getAction();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
        $this->assertEquals(json_encode($this->plugin_manager->getPluginConfiguration()), $response->getContent());
    }
}
