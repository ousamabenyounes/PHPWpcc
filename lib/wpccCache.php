<?php
require('wpcc.php');
require('wpccRequest.php');
require('wpccFile.php');
require('wpccPngToJpg.php');
require('wpccDirectory.php');
require('wpccUtils.php');

class wpccCache extends wpcc
{
    public static $cacheDir = 'cache/';
    public static $desktopFormat = '600X800';

    protected $_rootDir;
    protected $_cacheDir;
    protected $_twig;
    protected $_contentCacheDir;
    protected $_screenshotDir;
    protected $_thumbnailDir;
    protected $_errorCacheDir;

    public function __construct($root_dir)
    {
        $this->_rootDir = $root_dir;
        $this->initCacheDir();
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem($root_dir . 'views');
        $this->_twig = new Twig_Environment($loader, array('debug' => false));
    }

    /**
     * this method create cache directories (screenshot, content...)
     */
    public function initCacheDir()
    {
        $oldmask = umask(0);
        $this->_cacheDir = $this->_rootDir . self::$cacheDir . date("Ymd_H") . 'h' . '/';
        $this->_contentCacheDir = $this->_cacheDir . 'content/';
        $this->_screenshotDir = $this->_cacheDir . 'screenshot/';
        $this->_thumbnailDir = $this->_screenshotDir .'thumbnail/';;
        $this->_errorCacheDir = $this->_cacheDir . 'error/';
        wpccDirectory::createDirectory($this->_rootDir . self::$cacheDir, 0777);
        wpccDirectory::createDirectory($this->_cacheDir, 0777);
        wpccDirectory::createDirectory($this->_contentCacheDir, 0777);
        wpccDirectory::createDirectory($this->_screenshotDir, 0777);
        wpccDirectory::createDirectory($this->_thumbnailDir, 0777);
        wpccDirectory::createDirectory($this->_errorCacheDir, 0777);
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
        $moveTo = 4 + strlen($nbSites);
        foreach ($groupUrl as $portail => $sites) {
            foreach ($sites as $site) {
                echo "\033[" . $moveTo . "D";
                echo str_pad($nbSitesChecked, 3, ' ', STR_PAD_LEFT) . "/" . $nbSites;
                $cleanUrl = wpccUtils::urlToString($site);
                $fileName = $this->_contentCacheDir . $cleanUrl . '.php';
                $errorFileName = $this->_errorCacheDir . $cleanUrl . '.php';
                try {
                    $response = wpccFile::getContentFromFile($fileName, false);
                    $errorResponse = wpccFile::getContentFromFile($errorFileName, false);
                    if (false === $response && false === $errorResponse) {
                        $response = wpccRequest::sendRequest($site);
                        if ('all' === $type || 'content' === $type) {
                            wpccFile::writeToFile($fileName, $response);
                        }
                        $this->makeScreenshot($type, $cleanUrl, $site);
                    }
                } catch (Exception $e) {
                    // Request failed, we save it on an error file
                    wpccFile::writeToFile($errorFileName, $e->getMessage());
                }
                $nbSitesChecked++;
            }
        }
    }


    /**
     * This function make a screeshot from a given url
     *
     * @param $type
     * @param $cleanUrl
     * @param $site
     */
    public function makeScreenshot($type, $cleanUrl, $site)
    {
        if ('all' === $type || 'screenshot' === $type) {
            $screenShotFilename = $this->_screenshotDir . $cleanUrl;
            exec("pageres " . $site . " " . self::$desktopFormat .
                "  --filename " . $screenShotFilename . self::$desktopFormat);
            wpccPngToJpg::compress($this->_screenshotDir, $this->_thumbnailDir,
                $cleanUrl . self::$desktopFormat);
        }
    }

}

?>