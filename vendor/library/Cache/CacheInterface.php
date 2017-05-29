<?php

/**
 * interface.php
 *
 * */
namespace Skinny\Cache;

interface CacheInterface {

    public function get($key);

    public function set($key, $value, $seconds);

    public function delete($key);

    public function clear();
}
