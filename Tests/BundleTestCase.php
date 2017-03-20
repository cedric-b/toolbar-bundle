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

namespace BackBee\Bundle\ToolbarBundle\Tests;

use org\bovigo\vfs\vfsStream;

use BackBee\Bundle\ToolbarBundle\Toolbar;
use BackBee\Security\Token\BBUserToken;
use BackBee\Security\User;
use BackBee\Tests\Mock\MockBBApplication;

/**
 * Test case for Toolbar bundle.
 *
 * @copyright    ©2017 - Lp digital
 * @author       Charles Rouillon <charles.rouillon@lp-digital.fr>
 */
class BundleTestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Toolbar
     */
    protected $bundle;

    /**
     * Sets up the required fixtures.
     */
    public function setUp()
    {
        $mockConfig = [
            'ClassContent' => [],
            'Config' => [
                'bootstrap.yml' => file_get_contents(__DIR__ . '/Config/bootstrap.yml'),
                'bundles.yml' => file_get_contents(__DIR__ . '/Config/bundles.yml'),
                'config.yml' => file_get_contents(__DIR__ . '/Config/config.yml'),
                'services.yml' => file_get_contents(__DIR__ . '/Config/services.yml'),
            ],
            'cache' => [
                'container' => [],
                'twig' => []
            ],
        ];

        vfsStream::umask(0000);
        vfsStream::setup('repositorydir', 0777, $mockConfig);

        $mockApp = new MockBBApplication(null, null, false, $mockConfig, __DIR__ . '/../vendor');
        $this->bundle = $mockApp->getBundle('toolbar');
    }

    /**
     * Creates a user for the specified group and authenticates a BBUserToken with the newly created user.
     * Note that the token is setted into application security context.
     */
    protected function createAuthenticatedUser(array $roles = ['ROLE_API_USER'])
    {
        $user = new User();
        $user
                ->setEmail('user@backbee.com')
                ->setLogin('user')
                ->setPassword('pass')
                ->setApiKeyPrivate(uniqid('PRIVATE', true))
                ->setApiKeyPublic(uniqid('PUBLIC', true))
                ->setApiKeyEnabled(true)
        ;
        $token = new BBUserToken($roles);
        $token->setAuthenticated(true);
        $token
                ->setUser($user)
                ->setCreated(new \DateTime())
                ->setLifetime(300)
        ;
        $this->bundle->getApplication()->getSecurityContext()->setToken($token);
    }
}
