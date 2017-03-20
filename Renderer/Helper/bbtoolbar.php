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

use Symfony\Component\HttpFoundation\IpUtils;

use BackBee\Config\Config;
use BackBee\Renderer\AbstractRenderer;

/**
 *
 *
 * @category    BackBee
 * @package     BackBee\Bundle\ToolbarBundle
 * @subpackage  Helper
 * @copyright   Lp digital system
 * @author      e.chau <eric.chau@lp-digital.fr>
 */
class bbtoolbar extends AbstractHelper
{

    /**
     * The current bundle config.
     *
     * @var Config
     */
    private $config;

    /**
     * Helper constructir.
     *
     * @param AbstractRenderer $renderer
     */
    public function __construct(AbstractRenderer $renderer)
    {
        parent::__construct($renderer);

        $this->config = $this->getRenderer()
                ->getApplication()
                ->getContainer()
                ->get('bundle.toolbar.config');
    }

    /**
     *
     *
     * @return
     */
    public function __invoke()
    {

        $settings = $this->config->getSection('settings');
        $wrapper = (isset($settings['wrapper_toolbar_id'])) ? $settings['wrapper_toolbar_id'] : '';

        /* add option bo connect */
        $config = $this->config->getBundleConfig();
        $disableToolbar = !$this->checksWhitelist() || ((isset($config['disable_toolbar'])) ? $config['disable_toolbar'] : false);

        return $this->getRenderer()->partial('partials/bbtoolbar.twig', array('wrapper' => $wrapper, 'disableToolbar' => $disableToolbar));
    }

    /**
     * Checks for the client IP agains a possible whitelist setted.
     *
     * @return boolean
     */
    private function checksWhitelist()
    {
        $whitelist = $this->config->getSection('whitelist');

        if (!empty($whitelist)) {
            $request = $this->getRenderer()->getApplication()->getRequest();

            return IpUtils::checkIp($request->getClientIp(), $whitelist);
        }

        return true;
    }
}
