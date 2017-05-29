<?php 
namespace App\wechat\service;

use App\wechat\model\account;

class WechatAccount
{
    const UNAUTHORIZED = 1;
    protected static $account;

    public function __construct()
    {
        self::$account = new account();
    }

    public static function getAccount($id)
    {
        if(! ris_int($id))
        {
            return false;
        }

        $filter['id'] = (int)$id;

        $row = self::$account->getRow();

        return $row;
    }

    public static function getAccountByAppid($appid)
    {
        $filter['appid'] = $appid;
        $row = self::$account->getRow();

        return $row;
    }

    public static function checkStatus($account)
    {
        if(isset($account['status']) && $account['status'] == self::UNAUTHORIZED)
        {
            throw new \LogicException('公众号未授权');
        }

        return true;
    }

    public static function getAccountRow($filter)
    {
        return self::$account->getRow($filter);
    }

    public static function addAccount($account)
    {
        return self::$account->insert($account);
    }

    public static function upAccount($filter, $data)
    {
        return self::$account->update($data, $filter);
    }

    public static function delAccount($filter)
    {
        return self::$account->delete($filter);
    }
}
