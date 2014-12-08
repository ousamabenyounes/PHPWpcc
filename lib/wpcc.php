<?php

class wpcc
{

    /**
     * This function convert an url to a cleaned String (without http:// www. ...)
     *
     * @param string $url
     * @return string $cleanedString
     */
    public function urlToString($url)
    {
        $cleanedString = str_replace(
            array(' ', 'http://', 'www.'),
            array('-', '', ''),
            $url
        ); // Replaces all spaces with hyphens.
        $cleanedString = preg_replace('/[^A-Za-z0-9\-]/', '', $cleanedString); // Removes special chars.
        return $cleanedString;
    }


    /**
     * This function convert a textarea content to an array of string
     *
     * @param string $textareaContent
     * @param array $textareaArray
     */
    public function textareaToArray($textareaContent)
    {
        $text = trim($textareaContent);
        $textareaArray = explode("\n", $text);
        $textareaArray = array_filter($textareaArray, 'trim');

        return $textareaArray;
    }



}
?>