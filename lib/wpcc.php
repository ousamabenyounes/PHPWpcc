<?php

class wpcc
{
    public static $root_dir = '../';

    /**
     * This function clean a given string and return the cleaned content
     *
     * @param string $string
     * @return string $cleanedString
     */
    public function clean($string)
    {
        $string = str_replace(
            array(' ', 'http://', 'www.'),
            array('-', '', ''),
            $string
        ); // Replaces all spaces with hyphens.
        $cleanedString = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return $cleanedString;
    }

}
?>