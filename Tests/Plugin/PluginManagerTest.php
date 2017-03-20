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

namespace BackBee\Bundle\ToolbarBundle\Tests\Plugin;

use Symfony\Component\DependencyInjection\Definition;

use BackBee\Bundle\ToolbarBundle\Plugin\PluginManager;
use BackBee\Config\Config;
use BackBee\DependencyInjection\Container;

/**
 * Tests suite for PluginManager
 *
 * @author e.chau <eric.chau@lp-digital.fr>
 *
 * @coversDefaultClass \BackBee\Bundle\ToolbarBundle\Plugin\PluginManager
 */
class PluginManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BackBee\DependencyInjection\Container
     */
    private $container;

    /**
     * @var BackBee\Bundle\ToolbarBundle\Plugin\PluginManager
     */
    private $plugin_manager;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->container = new Container();
        $this->container->set('bundle.toolbar.config', new Config(__DIR__));

        $this->plugin_manager = new PluginManager($this->container);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->container = null;
        $this->plugin_manager = null;
    }

    public function testGetPlugins()
    {
        $definition = new Definition(__NAMESPACE__ . '\\TestPlugin');
        $definition->addTag(PluginManager::PLUGIN_SERVICE_TAG);
        $this->container->setDefinition('test_plugin', $definition);
        $definition = new Definition(__NAMESPACE__ . '\\TestPlugin');
        $definition->addTag(PluginManager::PLUGIN_SERVICE_TAG);
        $this->container->setDefinition('another_test_plugin', $definition);
        $plugins = $this->plugin_manager->getPlugins();

        $this->assertCount(2, $plugins);
        foreach ($plugins as $p) {
            $this->assertInstanceOf('BackBee\Bundle\ToolbarBundle\Plugin\PluginInterface', $p);
        }
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetInvalidPlugin()
    {
        $definition = new Definition('stdClass');
        $definition->addTag(PluginManager::PLUGIN_SERVICE_TAG);
        $this->container->setDefinition('invalid_plugin', $definition);
        $this->plugin_manager->getPlugins();
    }

    public function testGetPluginConfiguration()
    {
        $definition = new Definition(__NAMESPACE__ . '\\TestPlugin');
        $definition->addTag(PluginManager::PLUGIN_SERVICE_TAG);
        $this->container->setDefinition('test_plugin', $definition->setArguments(['test_plugin']));
        $definition = new Definition(__NAMESPACE__ . '\\TestPlugin');
        $definition->addTag(PluginManager::PLUGIN_SERVICE_TAG);
        $this->container->setDefinition('another_test_plugin', $definition->setArguments(['another_test_plugin']));

        $this->assertEquals([
            'plugins' => [
                'namespace' => [
                    'test_plugin'         => TestPlugin::TEST_NAMESPACE,
                    'another_test_plugin' => TestPlugin::TEST_NAMESPACE
                ],
                'test_plugin'         => TestPlugin::$test_configuration,
                'another_test_plugin' => TestPlugin::$test_configuration
            ]
        ], $this->plugin_manager->getPluginConfiguration());
    }
}
