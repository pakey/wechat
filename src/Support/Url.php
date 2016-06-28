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
 * Url.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace EasyWeChat\Support;

/**
 * Class Url.
 */
class Url
{

    /**
     * 获取微信使用的url 去除#后面之后的
     *
     * @return string
     */
    public static function current()
    {
        $url = self::full();
        if (strpos($url, '#')) {
            $url = explode('#', $url)['0'];
        }
        return $url;
    }

    /**
     * 获取全路径
     *
     * @return string
     */
    public static function full()
    {
        if (PHP_SAPI == 'cli') return '';
        $protocol = (!empty($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] === 443) ? 'https://' : 'http://';
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        } else {
            $host = $_SERVER['HTTP_HOST'];
        }
        return $protocol . $host . $_SERVER['REQUEST_URI'];
    }

}
