<?php 

function redirect($url)
{
    header('Location:' . $url);exit;
}

function baseUrl()
{
    $baseUrl = \request::getSchemeAndHttpHost() . \request::getBaseUrl();
    if(strpos($baseUrl, 'index.php') !== FALSE)
    {
        $baseUrl = str_replace('index.php', '', $baseUrl);
    }
    $baseUrl = rtrim($baseUrl, '/');
    
    return $baseUrl;
}

function url($action, array $params = [])
{
    $arr = explode('_', $action);
    if(count($arr) != 3)
    {
        throw new \RuntimeException('Must be a complete method');
    }

    $urlParmas['m'] = $arr[0];
    $urlParmas['c'] = $arr[1];
    $urlParmas['a'] = $arr[2];

    $urlParmas = array_merge($urlParmas, $params);
    $urlParmas = http_build_query($urlParmas);
    $url = \request::getSchemeAndHttpHost() . \request::getBaseUrl() . '?' . $urlParmas;

    return $url;
}

function random($length, $numeric = false)
{
    $seed = base_convert(md5(microtime()), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
    if ($numeric) 
    {
        $hash = '';
    } 
    else 
    {
        $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
        $length--;
    }
    
    $max = strlen($seed) - 1;
    for ($i = 0; $i < $length; $i++) 
    {
        $hash .= $seed{mt_rand(0, $max)};
    }
    //$hash=strtoupper($hash);

    return $hash;
}

/**
 * 判断是否是整数
 * @param mixed $number
 * 
 * @return bool
 * */
function ris_int($number)
{
    if(!is_numeric($number)){
        return false;
    }
    
    if(is_int($number))
    {
        return true;
    }
    
    if(floor($number) == $number)
    {
        return true;
    }
    
    return false;
}
