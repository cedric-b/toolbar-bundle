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

namespace BackBee\Bundle\ToolbarBundle;

use BackBee\BBApplication;
use BackBee\Bundle\AbstractBundle;
use BackBee\Config\Config;

/**
 * ToolbarBundle main class
 *
 * @category    BackBee\Bundle
 * @package     ToolbarBundle
 * @copyright   Lp digital system
 * @author      e.chau <eric.chau@lp-digital.fr>
 */
class Toolbar extends AbstractBundle
{
    /**
     * Custom method to tell Renderer where to go for retrieving ToolbarBundle's helpers directory
     *
     * @param  BBApplication $application
     * @param  Config        $config
     */
    public static function loadHelpers(BBApplication $application, Config $config)
    {
        $helperDir = __DIR__.DIRECTORY_SEPARATOR.'Renderer'.DIRECTORY_SEPARATOR.'Helper';
        if (!is_dir($helperDir)) {
            $application->error("Unable to load ToolbarBundle helpers directory (:$helperDir)");
        } else {
            $application->getRenderer()->addHelperDir($helperDir);
        }
    }

    /**
     * Custom method to tell Renderer where to go for retrieving ToolbarBundle's views directory
     *
     * @param  BBApplication $application
     * @param  Config        $config
     */
    public static function loadTemplates(BBApplication $application, Config $config)
    {
        $viewsDir = __DIR__.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'views';
        if (!is_dir($viewsDir)) {
            $application->error("Unable to load ToolbarBundle views directory (:$viewsDir)");
        } else {
            $application->getRenderer()->addScriptDir($viewsDir);
        }
    }

    /**
     * Loads ToolbarBundle and BBCoreJs resources directories into application.
     *
     * @param  BBApplication $application
     * @param  Config        $config
     */
    public static function loadResources(BBApplication $application, Config $config)
    {
        $baseDir = $application->getContainer()->get('bundle.toolbar')->getBaseDirectory();
        $application->addResourceDir($baseDir.DIRECTORY_SEPARATOR.'Resources');
        $application->addResourceDir(implode(DIRECTORY_SEPARATOR, [
            $application->getVendorDir(),
            'backbee',
            'backbee-js',
        ]));
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function stop()
    {
        return $this;
    }
}
