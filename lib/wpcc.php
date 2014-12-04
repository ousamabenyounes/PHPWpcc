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


    /**
     * This function write a given content to a specific file
     *
     * @param string $filename
     * @param string $content
     */
    public function writeToFile($filename, $content)
    {
        try {
            file_put_contents($filename, $content);
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
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