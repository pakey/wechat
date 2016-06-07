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
 * ComponentAccessToken.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace EasyWeChat\Component;

use EasyWeChat\Core\AccessToken;
use Doctrine\Common\Cache\Cache;
use EasyWeChat\Core\Exceptions\HttpException;

/**
 * Class ComponentAccessToken.
 */
class ComponentAccessToken extends AccessToken
{

    /**
     * component verify ticket
     *
     * @var string
     */
    protected $ticket;

    protected $prefix = 'easywechat.component.access_token.';

    // API
    const API_TOKEN_GET = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';

    /**
     * Constructor.
     *
     * @param string                       $appId
     * @param string                       $secret
     * @param string                       $ticket
     * @param \Doctrine\Common\Cache\Cache $cache
     */
    public function __construct($appId, $secret, $ticket, Cache $cache = null)
    {
        $this->appId  = $appId;
        $this->secret = $secret;
        $this->ticket = $ticket;
        $this->cache  = $cache;
    }

    /**
     * Get token from WeChat API.
     *
     * @param bool $forceRefresh
     *
     * @return string
     */
    public function getToken($forceRefresh = false)
    {
        $cacheKey = $this->prefix . $this->appId;

        $cached = $this->getCache()->fetch($cacheKey);

        if ($forceRefresh || empty($cached)) {
            $token = $this->getTokenFromServer();

            // XXX: T_T... 7200 - 1500
            $this->getCache()->save($cacheKey, $token['component_access_token'], $token['expires_in'] - 1500);

            return $token['component_access_token'];
        }

        return $cached;
    }

    /**
     * Get the access token from WeChat server.
     *
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     *
     * @return array|bool
     */
    public function getTokenFromServer()
    {
        $params = [
            'component_appid'         => $this->appId,
            'component_appsecret'     => $this->secret,
            'component_verify_ticket' => $this->ticket,
        ];

        $http = $this->getHttp();

        $token = $http->parseJSON($http->json(self::API_TOKEN_GET, $params));

        if (empty($token['component_access_token'])) {
            throw new HttpException('Request AccessToken fail. response: ' . json_encode($token, JSON_UNESCAPED_UNICODE));
        }

        return $token;
    }
}
