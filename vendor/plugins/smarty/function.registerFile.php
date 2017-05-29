<?php 
function smarty_function_registerFile($params, $template)
{
    $type = $params['type'];
    $baseUrl = \request::getSchemeAndHttpHost() . \request::getBaseUrl();

    $file = trim(STATIC_DIR, '/'). '/' . trim($params['file'], '/');
    $baseUrl = baseUrl();

    $file = $baseUrl . '/' .$file;

    $script = '<script>%s</script>';
    switch ($type) 
    {
        case 'js':
            $str = 'dynamicLoading.js("'.$file.'");';
            break;
        case 'css':
            $str = 'dynamicLoading.css("'.$file.'");';
            break;
    }
    $str = sprintf($script, $str);

    return $str;
}
