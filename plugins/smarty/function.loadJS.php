<?php 
function smarty_function_loadJS($params, $template)
{
    $baseUrl = baseUrl();
    $file = trim(STATIC_DIR, '/'). '/' . trim($params['file'], '/');
    $file = $baseUrl . '/' .$file;
    $str = '<script src="' . $file . '" type="text/javascript"></script>';

    return $str;
}
