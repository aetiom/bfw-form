<?php

namespace BfwForm\Helpers;

class Secure extends \BFW\Helpers\Secure
{
    public static function secureKnownType($data, string $type)
    {
        if ($type === 'url') {
            return filter_var($data, FILTER_VALIDATE_URL);
        }

        return parent::secureKnownType($data, $type);
    }
}