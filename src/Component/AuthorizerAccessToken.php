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
 * AuthorizerAccessToken.php.
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
 * Class AuthorizerAccessToken.
 */
class AuthorizerAccessToken extends AccessToken
{

    /**
     * Componect Config
     *
     * @var array
     */
    protected $componectConfig;

    /**
     * Compconent AccessToken
     *
     * @var ComponentAccessToken
     */
    protected $compconentAccessToken;

    protected $prefix = 'easywechat.authorizer.access_token.';

    // API
    const API_TOKEN_GET = 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token';


    /**
     * AuthorizerAccessToken constructor.
     *
     * @param string     $appId
     * @param string     $componectConfig
     * @param Cache|null $cache
     * @param            $compconentAccessToken
     */
    public function __construct($appId, $componectConfig, Cache $cache = null, $compconentAccessToken)
    {
        $this->appId                 = $appId;
        $this->compconentAccessToken = $compconentAccessToken;
        $this->componectConfig       = $componectConfig;
        $this->cache                 = $cache;
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
            $this->getCache()->save($cacheKey, $token['authorizer_access_token'], $token['expires_in'] - 1500);

            return $token['authorizer_access_token'];
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
            'component_appid'          => $this->componectConfig['app_id'],
            'authorizer_appid'         => $this->appId,
            'authorizer_refresh_token' => $this->componectConfig['refresh_token'],
        ];


        $http = $this->getHttp();

        $token = $http->parseJSON($http->json(self::API_TOKEN_GET . '?component_access_token=' . $this->compconentAccessToken->getToken(), $params));

        var_dump($params, $token);
        if (empty($token['authorizer_access_token'])) {
            throw new HttpException('Request AccessToken fail. response: ' . json_encode($token, JSON_UNESCAPED_UNICODE));
        }

        return $token;
    }
}
