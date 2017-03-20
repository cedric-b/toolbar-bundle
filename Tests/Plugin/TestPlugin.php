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

use BackBee\Bundle\ToolbarBundle\Plugin\PluginInterface;

/**
 * TestPlugin is used by ToolbarBundle test suite
 *
 * @author e.chau <eric.chau@lp-digital.fr>
 */
class TestPlugin implements PluginInterface
{
    const TEST_NAME = 'test_plugin';
    const TEST_NAMESPACE = 'path/to/test/plugin/sources';

    /**
     * @var array
     */
    public static $test_configuration = ['applyOn' => ['Element/text', 'Element/image']];

    /**
     * @var string
     */
    private $name;

    /**
     * TestPlugin's constructor
     *
     * @param string $name
     */
    public function __construct($name = self::TEST_NAME)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return self::TEST_NAMESPACE;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return self::$test_configuration;
    }
}
