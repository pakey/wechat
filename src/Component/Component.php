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
 * Component.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace EasyWeChat\Component;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache;
use EasyWeChat\Component\ComponentAccessToken;
use EasyWeChat\Core\AbstractAPI;
use EasyWeChat\Foundation\Config;
use EasyWeChat\Support\Str;
use EasyWeChat\Support\Url as UrlHelper;
use EasyWeChat\Support\Url;
use Illuminate\Support\Facades\Input;

/**
 * 授权
 * Class Component.
 */
class Component extends AbstractAPI
{


    /**
     * The request token.
     *
     * @var ComponentAccessToken
     */
    protected $accessToken;

    /**
     * 返回地址
     *
     * @var string
     */
    protected $redirectUri;

    /**
     * 授权公众号appid
     *
     * @var string
     */
    protected $authAppId;

    /**
     * 授权码
     *
     * @var string
     */
    protected $authCode;

    const API_AUTH_CODE     = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage';
    const API_AUTH_INFO     = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth';
    const API_PRE_AUTH_CODE = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode';
    const API_ACCOUNT_INFO  = 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info';

    const API_OAUTH_URL           = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    const API_OAUTH_ACCESS_TOKEN  = 'https://api.weixin.qq.com/sns/oauth2/component/access_token';
    const API_OAUTH_REFERSH_TOKEN = 'https://api.weixin.qq.com/sns/oauth2/component/refresh_token';
    const API_OAUTH_INFO          = 'https://api.weixin.qq.com/sns/userinfo';

    /**
     * 获取授权公众号的授权信息
     *
     * @return array
     */
    public function getAuthInfo()
    {
        $url    = self::API_AUTH_INFO . '?component_access_token=' . $this->accessToken->getToken();
        $data   = [
            'component_appid'    => $this->accessToken->getAppId(),
            'authorization_code' => $this->getAuthCode(),
        ];
        $result = $this->parseJSON('json', [$url, $data]);

        $this->setAuthAppId($result['authorization_info']['authorizer_appid']);

        return $result['authorization_info'];
    }

    /**
     * 获取授权公众号的账户信息
     *
     * @return array
     */
    public function getAccountInfo($appId = null)
    {
        $appId  = $appId ?: $this->getAuthAppId();
        $url    = self::API_ACCOUNT_INFO . '?component_access_token=' . $this->accessToken->getToken();
        $data   = [
            'component_appid'  => $this->accessToken->getAppId(),
            'authorizer_appid' => $appId,
        ];
        $result = $this->parseJSON('json', [$url, $data]);

        return $result->toArray();
    }

    /**
     * 获取授权码 authCode
     *
     * @return mixed
     */
    public function getAuthCode()
    {
        if ($this->authCode) {
            return $this->authCode;
        } else if (isset($_GET['auth_code'])) {
            return $this->authCode = $_GET['auth_code'];
        } else {
            $url = $this->getAuthUrl();
            header('location:' . $url);
        }
    }

    /**
     * 设置授权码 authCode
     *
     * @param $auth_code
     */
    public function setAuthCode($auth_code)
    {
        $this->authCode = $auth_code;
    }

    /**
     * 获取预授权 pre_auth_code
     *
     * @return mixed
     */
    public function getPreAuthCode()
    {
        $url    = self::API_PRE_AUTH_CODE . '?component_access_token=' . $this->accessToken->getToken();
        $data   = [
            'component_appid' => $this->accessToken->getAppId(),
        ];
        $result = $this->parseJSON('json', [$url, $data]);

        return $result['pre_auth_code'];
    }

    /**
     * 获取授权地址
     *
     * @param null $redirect_uri
     * @return string
     */
    protected function getAuthUrl($redirect_uri = null)
    {
        $redirect_uri = $redirect_uri ?: $this->getRedirectUri();
        $data         = [
            'component_appid' => $this->accessToken->getAppId(),
            'pre_auth_code'   => $this->getPreAuthCode(),
            'redirect_uri'    => $redirect_uri,
        ];
        return self::API_AUTH_CODE . '?' . http_build_query($data);
    }

    /**
     * 获取跳转地址
     *
     * @return string
     */
    public function getRedirectUri()
    {
        if ($this->redirectUri) {
            return $this->redirectUri;
        }
        return UrlHelper::current();
    }

    /**
     * 设置跳转地址
     *
     * @param $uri
     */
    public function setRedirectUri($uri)
    {
        $this->redirectUri = $uri;
    }

    /**
     * 获取授权公众号 appid
     *
     * @return string
     */
    public function getAuthAppId()
    {
        return $this->authAppId;
    }

    /**
     * 设置授权公众号 appid
     *
     * @return string
     */
    public function setAuthAppId($appId)
    {
        $this->authAppId = $appId;
    }
    
    public function oauth($scope = 'snsapi_userinfo', $callBackUrl = "")
    {
        
        if (Input::get('state')) {
            if ($code = Input::get('code')) {
                $token = $this->oauthGetAccessToken($code);
                $info = $this->oauthUser($token['access_token'], $token['openid']);
                return $info;
            }
        } else {
            $this->oauthRedirect($scope, $callBackUrl);
            exit;
        }
    }
    
    public function oauthRedirect($scope = 'snsapi_userinfo', $callBackUrl = "")
    {
        $callBackUrl = $callBackUrl ?: Url::full();
        $params      = [
            'appid'           => $this->accessToken->getConfig('app_id'),
            'redirect_uri'    => $callBackUrl,
            'response_type'   => 'code',
            'scope'           => $scope,
            'state'           => md5($callBackUrl),
            'component_appid' => $this->accessToken->getAppId(),
        ];
        $url         = self::API_OAUTH_URL . '?' . http_build_query($params) . '#wechat_redirect';
        redirect($url)->send();
    }
    
    public function oauthGetAccessToken($code)
    {
        $params = [
            'appid'                  => $this->accessToken->getConfig('app_id'),
            'code'                   => $code,
            'grant_type'             => 'authorization_code',
            'component_access_token' => $this->accessToken->getToken(),
            'component_appid'        => $this->accessToken->getAppId(),
        ];
        $result = $this->parseJSON('get', [self::API_OAUTH_ACCESS_TOKEN, $params]);
        
        return $result;
    }
    
    public function oauthUser($token, $openid)
    {
        $data = [
            'access_token' => $token,
            'openid'       => $openid,
            'lang'         => 'zh_CN',
        ];
        
        $result = $this->parseJSON('post', [self::API_OAUTH_INFO, $data]);
        
        return $result;
    }
}