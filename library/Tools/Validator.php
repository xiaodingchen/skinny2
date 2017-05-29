<?php

namespace Skinny\Tools;

use Skinny\Tools\Validator\Validator as Valid;

class Validator {

    /**
     * Create a new Validator instance.
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @return Valid
     */
    public static function make(array $data, array $rules, array $messages = array())
    {

        $validator = new Valid($data, $rules, $messages);

        return $validator;

    }

}
