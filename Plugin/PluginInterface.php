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

/**
 * PluginInterface allows to declare new toolbar plugin for handling contents
 *
 * @category  BackBee
 * @package   BackBee\Bundle\ToolbarBundle
 * @copyright Lp digital system
 * @author    e.chau <eric.chau@lp-digital.fr>
 */
interface PluginInterface
{
    /**
     * Returns plugin unique name (must be in lower case and not containing whitespace, use underscore instead)
     *
     * @return string
     */
    public function getName();

    /**
     * Return plugin namespace
     *
     * @return string
     */
    public function getNamespace();

    /**
     * Returns plugin configuration
     *
     * @return array
     */
    public function getConfig();

}
