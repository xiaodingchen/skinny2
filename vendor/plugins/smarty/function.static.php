<?php 
function smarty_function_static($params, $template)
{
    $baseUrl = baseUrl();
    $file = trim(STATIC_DIR, '/');
    $str = $baseUrl . '/' .$file;

    return $str;
}
