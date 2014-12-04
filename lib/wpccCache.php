<?php

require('wpcc.php');
require('wpccRequest.php');

class wpccCache extends wpcc
{
    public static $cacheDir = 'cache/';
    protected $_cacheDir;
    protected $_twig;
    protected $_contentCacheDir;
    protected $_screenshotCacheDir;
    protected $_errorCacheDir;

    public function __construct()
    {
        $this->initCacheDir();
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem(wpcc::$root_dir.'views');
        $this->_twig = new Twig_Environment($loader, array('debug' => false));
    }

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
     * this method create cache directories (screenshot, content...)
     */
    public function initCacheDir()
    {
        $oldmask = umask(0);
        $this->_cacheDir = self::$root_dir . self::$cacheDir . date("Ymd_H") . 'h' . '/';
        $this->_contentCacheDir = $this->_cacheDir . 'content/';
        $this->_screenshotCacheDir = $this->_cacheDir . 'screenshot/';
        $this->_errorCacheDir = $this->_cacheDir . 'error/';
        if (!is_dir($this->_cacheDir)) {
            mkdir($this->_cacheDir, 0777);
        }
        if (!is_dir($this->_contentCacheDir)) {
            mkdir($this->_contentCacheDir, 0777);
        }
        if (!is_dir($this->_screenshotCacheDir)) {
            mkdir($this->_screenshotCacheDir, 0777);
        }
        if (!is_dir($this->_errorCacheDir)) {
            mkdir($this->_errorCacheDir, 0777);
        }
        umask($oldmask);
    }


    /**
     * This method will parse All Web Page and store the HTML content on cache files.
     *
     * @param string $groupUrl
     */
    public function generateCache($groupUrl, $type = 'all')
    {
        $wpccRequest = new wpccRequest();
        foreach ($groupUrl as $portail => $sites) {
            foreach ($sites as $site) {
                $cleanUrl = $this->clean($site);
                $fileName = $this->_contentCacheDir . $cleanUrl . '.php';
                $errorFileName = $this->_errorCacheDir . $cleanUrl . '.php';
                $screenShotName = $this->_screenshotCacheDir . $cleanUrl;
                echo ($portail . ' => ' . $site . "\r\n");
                try {
                    $response = @file_get_contents($fileName);
                    $errorResponse = @file_get_contents($errorFileName);
                    //echo 'Searching for ' . $fileName . "\r\n";
                    if (false === $response && false === $errorResponse) {
                        echo ("Generating cache ...\r\n");
                        $response = $wpccRequest->sendRequest($site);
                        if ('all' === $type || 'content' === $type) {
                            file_put_contents($fileName, $response);
                        }
                        if ('all' === $type || 'screenshot' === $type) {
                            exec("pageres " . $site . " 1024x768 --filename '" . $screenShotName . "' ", $output);
                        }
                    }
                } catch (Exception $e) {
                    file_put_contents($errorFileName, $e->getMessage());
                }
            }
        }
    }
}
?>