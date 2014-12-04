<?php

require('wpcc.php');
require('wpccRequest.php');

class wpccCache extends wpcc
{
    public static $cacheDir = 'cache/';
    public static $desktopFormat = '1024x768';
    public static $mobileFormat = '480X600';

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
     * this method create cache directories (screenshot, content...)
     */
    public function initCacheDir()
    {
        $oldmask = umask(0);
        $this->_cacheDir = self::$root_dir . self::$cacheDir . date("Ymd_H") . 'h' . '/';
        $this->_contentCacheDir = $this->_cacheDir . 'content/';
        $this->_screenshotCacheDir = $this->_cacheDir . 'screenshot/';
        $this->_errorCacheDir = $this->_cacheDir . 'error/';
        if (!is_dir( self::$root_dir . self::$cacheDir )) {
            mkdir( self::$root_dir . self::$cacheDir , 0777);
        }
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
     * This method just print intro informations
     *
     * @param string $type
     */
    public function printIntro($type)
    {
        echo "\r\nWpcc Cache Generation      \r\n";
        if ('all' === $type || 'content' === $type) {
            echo " - Generating All Websites HTML Cache \r\n";
        }
        if ('all' === $type || 'screenshot' === $type) {
            echo " - Generating All WebSites screenshots \r\n";
        }
        echo "Progress :      ";  // 5 characters of padding at the end
    }

    /**
     * This method will parse All Web Page and store the HTML content on cache files.
     *
     * @param string $groupUrl
     * @param string $type
     */
    public function generateCache($groupUrl, $type = 'all')
    {
        $this->printIntro($type);
        $nbSites = count($groupUrl, COUNT_RECURSIVE) - count($groupUrl);
        $nbSitesChecked = 0;
        $wpccRequest = new wpccRequest();
        $moveTo = 4 + strlen($nbSites);
        foreach ($groupUrl as $portail => $sites) {
            foreach ($sites as $site) {
                echo "\033[" . $moveTo . "D";
                echo str_pad($nbSitesChecked, 3, ' ', STR_PAD_LEFT) . "/" . $nbSites;
                $cleanUrl = $this->clean($site);
                $fileName = $this->_contentCacheDir . $cleanUrl . '.php';
                $errorFileName = $this->_errorCacheDir . $cleanUrl . '.php';
                $screenShotFilename = $this->_screenshotCacheDir . $cleanUrl;
                try {
                    $response = @file_get_contents($fileName);
                    $errorResponse = @file_get_contents($errorFileName);
                    if (false === $response && false === $errorResponse) {
                        $response = $wpccRequest->sendRequest($site);
                        if ('all' === $type || 'content' === $type) {
                            $this->writeToFile($fileName, $response);
                        }
                        if ('all' === $type || 'screenshot' === $type) {
                            exec("pageres " . $site . " " . self::$desktopFormat . "  --filename " .
                                $screenShotFilename . self::$desktopFormat, $output);                  }
                    }
                } catch (Exception $e) {
                    // Request failed, we save it on an error file
                    $this->writeToFile($errorFileName, $e->getMessage());
                }
                $nbSitesChecked++;
            }
        }
    }
}
?>