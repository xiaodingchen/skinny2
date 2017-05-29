<?php 
namespace App\wechat\service;

use Skinny\Component\Config as config;
use EasyWeChat\Foundation\Application;
use App\wechat\service\WechatAccount;

class Wechat
{
    protected static $_wechatApp = null;
    protected static $_static = null;

    public static function instance()
    {
        if(! self::$_static instanceof self)
        {
            self::$_static = new self;
        }

        return self::$_static;
    }


    /**
     * 通过微信公众号信息取得一个Application实例
     *
     * @param array $account  关联数组：type和appid两个是必须的，type是接入模式，appid是接入公众号的app_id
     *
     * @return \EasyWeChat\Foundation\Application
     */
    public function wechatApp($account)
    {
        if(! isset($account['mode']) || ! isset($account['appid']) || !isset($account['status']))
        {
            throw new \LogicException('缺少公众号关键信息，appid或接入模式或授权状态');
        }

        WechatAccount::checkStatus($account);

        if($account['mode'] == 'normal')
        {
            $secret = isset($account['secret']) ? $account['secret'] : null;
            $encodingaeskey = isset($account['encodingaeskey']) ? $account['encodingaeskey'] : null;
            
            return $this->normal($account['appid'], $secret, $encodingaeskey);
        }

        // todo:微信开放授权模式，待完善
        if($account == 'authorizer')
        {
            return $this->authorizer();
        }

        throw new \LogicException('缺少公众号关键信息：接入信息错误');
        
    }
    

    /**
     * 获取一个微信实例
     */
    public function getWechatApp()
    {
        if(! self::$_wechatApp instanceof Application)
        {
            $config = config::get('wechat.easywechat.debug');
            self::$_wechatApp = new Application($config);
        }

        return self::$_wechatApp;
    }

    /**
     * 微信普通模式
     *
     * @param $appid string 微信公众号appid
     * @param $secret string 和appid相关联的appsecret
     * @param $encodingaeskey string EncodingAESKey，安全模式下请一定要填写！！！
     * @return \EasyWeChat\Foundation\Application
     */
    public function normal($appId, $secret = null, $encodingaeskey = null)
    {
        $app = $this->getWechatApp();
        $app['config']->set('app_id', $appId);

        if(! $secret || ! $encodingaeskey)
        {
            $row = $this->getWechatRow($appId);
            if($row)
            {
                $secret = $row['secret'];
                $encodingaeskey = $row['encodingaeskey'];
            }
            
        }

        $app['config']->set('secret', $secret);
        $app['config']->set('aes_key', $encodingaeskey);
        $app->access_token->setCacheKey($appId);

        return $app;

    }

    /**
     * 微信授权模式
     *
     * @param $appid string 微信公众号appid
     * @return \EasyWeChat\Foundation\Application
     */

    public function authorizer(array $options = [])
    {
        $app = $this->getWechatApp();
        $app['config']->set('open_platform', $options);

        return $app;
    }

    protected function getWechatRow($appId)
    {
        $account = WechatAccount::getAccountByAppid($appid);
        if(! $account)
        {
            throw new \LogicException("公众号不存在");
        }

        WechatAccount::checkStatus($account);

        return $account;
    }



}
