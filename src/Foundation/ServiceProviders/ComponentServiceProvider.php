<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * BroadcastServiceProvider.php.
 *
 * This file is part of the wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace EasyWeChat\Foundation\ServiceProviders;

use EasyWeChat\Component\AuthorizerAccessToken;
use EasyWeChat\Component\Component;
use EasyWeChat\Component\ComponentAccessToken;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ComponentServiceProvider.
 */
class ComponentServiceProvider implements ServiceProviderInterface{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $compconentAccessToken=new ComponentAccessToken(
            $pimple['config']['component']['app_id'],
            $pimple['config']['component']['secret'],
            $pimple['config']['component']['ticket'],
            $pimple['cache']
        );
        $pimple['access_token']=new AuthorizerAccessToken(
            $pimple['config']['app_id'],
            $pimple['config']['component'],
            $compconentAccessToken,
            $pimple['cache']
        );
        
        $pimple['component'] =  new Component($compconentAccessToken);
    }
}