<?php

/**
 * @param string $firstname
 * @param string $lastname
 * @return string
 */
function fullname(string $firstname, string $lastname) :string
{
    return strtoupper($lastname) . ' ' . ucfirst(strtolower($firstname));
}
