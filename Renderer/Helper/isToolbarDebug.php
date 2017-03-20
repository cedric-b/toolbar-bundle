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

namespace BackBee\Renderer\Helper;

/**
 * @category    BackBee
 * @package     BackBee\Renderer
 * @subpackage  Helper
 * @copyright   Lp digital system
 * @author      n.dufreche <nicolas.dufreche@lp-digital.fr>
 */
class isToolbarDebug extends AbstractHelper
{
    /**
     * Return true if the application is in the debug mode.
     *
     * @return boolean
     */
    public function __invoke()
    {
        $config = $this->getRenderer()->getApplication()->getContainer()->get('bundle.toolbar.config')->getBundleConfig();
        return array_key_exists('debug', $config) ? $config['debug'] : false;

    }
}
