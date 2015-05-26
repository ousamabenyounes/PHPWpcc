<?php

namespace Wpcc;

use Symfony\Component\HttpFoundation\Request;

class Utils
{
    const GET = 1;
    const POST = 2;

    /**
     * This function convert an url to a cleaned String (without http:// www. ...)
     *
     * @param string $url
     * @return string $cleanedString
     */
    public static function urlToString($url)
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
    public static function textareaToArray($textareaContent)
    {
        $text = trim($textareaContent);
        $textareaArray = explode("\n", $text);
        $textareaArray = array_filter($textareaArray, 'trim');

        return $textareaArray;
    }


    /**
     * @param string $domainName
     * @return string $domainName
     */
    public static function getDomainWithoutExtention($domainName)
    {
        $domainName = str_replace(
            array('&amp;', 'http://', '.'),
            array('', '', ' '),
            $domainName
        );
        $domainName = ucwords(strtolower($domainName));
        $domainName = str_replace('/', " ", $domainName);
        $domainName = ucwords(strtolower($domainName));
        $domainName = str_replace('-', " ", $domainName);
        $domainName = ucwords(strtolower($domainName));

        return (
        str_replace(
            array('&', ' ', '/', 'www', '-', '.', '?', '=', '_'),
            array('', '', '', '', '', '', '', '', ''),
            $domainName
        )
        );
    }

    /**
     * Print simple progress bar.
     *
     * @access public
     * @param int $processed amount of items processed
     * @param int $max maximum amount of items to process
     * @return void
     */
    public static function progress($processed, $max, $time1)
    {
        if (0 !== $processed) {
            $progress = round($processed / ($max / 100), 2);
            $progress_points = floor($progress / 2);
            $time_x = time();
            $timediff = $time_x - $time1;
            $estimation = round((((100 / $progress * $timediff) - $timediff) / 60), 2);
            echo str_pad(str_repeat("#", $progress_points), 52, " ", STR_PAD_RIGHT) . sprintf("%.2f", $progress) .
                str_pad("% ( " . sprintf("%.2f", $estimation) . " min left )", 27, " ", STR_PAD_RIGHT) . "\r";
        }
    }

    /**
     * Print simple progress bar.
     *
     * @access public
     * @param  string $varName
     * @param  string $type
     * @param  string $varContent
     *
     * @return string $varContent
     */
    public static function getVar($varName, $type = self::GET, $varContent = null)
    {
	$request = Request::createFromGlobals();
        if (self::GET === $type && isset($_GET[$varName]))
        {
	    $varContent = $request->query->get($varName);
        } elseif (self::POST === $type && isset($_POST[$varName])) {
	    $varContent = $request->request->get($varName);
        }
        return trim($varContent);
	
    }


    /**
     * @param string $cmd
     * @param bool $debug
     */
    public static function execCmd($cmd, $debug = false)
    {
        if (true === $debug)
            echo $cmd;
        exec ($cmd);
    }
}

?>
