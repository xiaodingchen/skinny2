<?php 
function smarty_function_loadCSS($params, $template)
{
    $baseUrl = $baseUrl = baseUrl();
    $file = trim(STATIC_DIR, '/'). '/' . trim($params['file'], '/');
    $file = $baseUrl . '/' .$file;
    $str = '<link rel="stylesheet" href="' . $file . '" type="text/css">';

    return $str;
}
