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

namespace BackBee\Bundle\ToolbarBundle\Tests\Renderer\Helper;

use BackBee\Bundle\ToolbarBundle\Tests\BundleTestCase;

/**
 * Tests suite for BackBee\Renderer\Helper\bbtoolbar
 *
 * @copyright    ©2017 - Lp digital
 * @author       Charles Rouillon <charles.rouillon@lp-digital.fr>
 * @covers       BackBee\Renderer\Helper\bbtoolbar
 */
class bbtoolbarTest extends BundleTestCase
{

    /**
     * @covers BackBee\Renderer\Helper\bbtoolbar::__invoke()
     */
    public function testInvokeEnabledWithoutToken()
    {
        $bbtoolbar = $this->bundle->getApplication()->getRenderer()->bbtoolbar();
        $this->assertTrue(0 < strpos($bbtoolbar, 'toolbar/dist/css/bb-ui-login.min.css'));
        $this->assertTrue(0 < strpos($bbtoolbar, $this->bundle->getConfig()->getSettingsConfig()['wrapper_toolbar_id']));
    }

    /**
     * @covers BackBee\Renderer\Helper\bbtoolbar::__invoke()
     */
    public function testInvokeEnabledWithToken()
    {
        $this->createAuthenticatedUser();

        $bbtoolbar = $this->bundle->getApplication()->getRenderer()->bbtoolbar();
        $this->assertTrue(0 < strpos($bbtoolbar, 'toolbar/dist/css/bb-ui.min.css'));
        $this->assertTrue(0 < strpos($bbtoolbar, $this->bundle->getConfig()->getSettingsConfig()['wrapper_toolbar_id']));
    }

    /**
     * @covers BackBee\Renderer\Helper\bbtoolbar::__invoke()
     */
    public function testInvokeEnabledWhitelist()
    {
        $this->bundle->getConfig()->setSection('whitelist', ['127.0.0.1']);
        $request = $this->bundle->getApplication()->getRequest();
        $request->server->set('REMOTE_ADDR', '127.0.0.1');

        $bbtoolbar = $this->bundle->getApplication()->getRenderer()->bbtoolbar();
        $this->assertTrue(0 < strpos($bbtoolbar, 'toolbar/dist/css/bb-ui-login.min.css'));
    }

    /**
     * @covers BackBee\Renderer\Helper\bbtoolbar::__invoke()
     */
    public function testInvokeDisabledWhitelist()
    {
        $this->bundle->getConfig()->setSection('whitelist', ['127.0.0.2']);
        $request = $this->bundle->getApplication()->getRequest();
        $request->server->set('REMOTE_ADDR', '127.0.0.1');

        $bbtoolbar = $this->bundle->getApplication()->getRenderer()->bbtoolbar();
        $this->assertTrue(empty($bbtoolbar));
    }
}
