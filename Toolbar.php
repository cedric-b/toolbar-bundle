<?php

/*
 * Copyright (c) 2011-2013 Lp digital system
 *
 * This file is part of BackBuilder5.
 *
 * BackBuilder5 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * BackBuilder5 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with BackBuilder5. If not, see <http://www.gnu.org/licenses/>.
 */

namespace BackBuilder\Bundle\ToolbarBundle;

use BackBuilder\BBApplication;
use BackBuilder\Bundle\AbstractBaseBundle;
use BackBuilder\Config\Config;

/**
 * ToolbarBundle main class
 *
 * @category    BackBuilder\Bundle
 * @package     ToolbarBundle
 * @copyright   Lp digital system
 * @author      e.chau <eric.chau@lp-digital.fr>
 */
class Toolbar extends AbstractBaseBundle
{
    /**
     * Custom method to tell Renderer where to go for retrieving ToolbarBundle's helpers directory
     *
     * @param  BBApplication $application
     * @param  Config        $config
     */
    public static function loadHelpers(BBApplication $application, Config $config)
    {
        $helper_dir = __DIR__ . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'helpers';
        if (false === is_dir($helper_dir)) {
            $application->error("Unable to load ToolbarBundle helpers directory (:$helper_dir)");
        } else {
            $application->getRenderer()->addHelperDir($helper_dir);
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
        $views_dir = __DIR__ . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'views';
        if (false === is_dir($views_dir)) {
            $application->error("Unable to load ToolbarBundle views directory (:$views_dir)");
        } else {
            $application->getRenderer()->addScriptDir($views_dir);
        }
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