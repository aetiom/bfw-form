<?php

namespace BfwForm\Helpers\Data;

/**
 * BFW Form data helpers interface
 *
 * @author Aetiom <aetiom@protonmail.com>
 * @package BFW-Form
 * @version 1.0
 */
interface DataInterface
{
    public static function validate(string $value, array $options);
}