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

use BackBee\Controller\ResourceController as BackBeeResourceController;

/**
 * @author Eric Chau <eric.chau@lp-digital.fr>
 */
class ResourceController extends BackBeeResourceController
{
    /**
     * {@inheritdoc}
     *
     * This method override BackBee\Controller\ResourceController::resourcesAction to handle toolbar/ resources.
     */
    public function resourcesAction($filename, $baseDir = null)
    {
        if (0 === strpos($filename, 'toolbar/')) {
            $filename = substr($filename, 8);
        }

        return parent::resourcesAction($filename, $baseDir);
    }
}
