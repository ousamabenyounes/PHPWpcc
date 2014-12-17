<?php
require('wpcc.php');
require('wpccPngToJpg.php');
require('wpccDirectory.php');

class wpccCache extends wpcc
{
    public static $cacheDir = 'cache/';
    public static $currentCache = 'current/';
    public static $desktopFormat = '600X800';
    public static $excludeScrenShot = 'api.lereferentiel.francetv.fr';

    protected $_rootDir;
    protected $_cacheDir;
    protected $_contentCacheDir;
    protected $_screenshotDir;
    protected $_thumbnailDir;
    protected $_errorCacheDir;
    protected $_time;

    public function __construct($root_dir)
    {
        $this->_rootDir = $root_dir;
        $this->_time = time();
        $this->initCacheDir();
    }

    /**
     * @return string $_contentCacheDir
     */
    public function getContentCacheDir()
    {
        return $this->_contentCacheDir;
    }

    /**
     * this method create cache directories (screenshot, content...)
     */
    public function initCacheDir()
    {
        $oldmask = umask(0);
        //$this->_cacheDir = $this->_rootDir . self::$cacheDir . date("Ymd_H") . 'h' . '/';
        $this->_cacheDir = $this->_rootDir . self::$cacheDir . self::$currentCache . '/';
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
    }


    /**
     * @param $page
     * @param $fileName
     * @param $cleanUrl
     * @param string $type
     */
    public static function generatePageCache($page, $fileName, $cleanUrl, $type = 'all')
    {
        $response = wpccRequest::sendRequest($page);
        if ('all' === $type || 'content' === $type) {
            wpccFile::writeToFile($fileName, $response);
        }
        return $response;
    }


    /**
     * This method will parse All Web Page and store the HTML content on cache files.
     *
     * @param string $groupUrl$response
     * @param string $type
     */
    public function generateCache($groupUrl, $type = 'all')
    {
        $this->printIntro($type);
        $nbSites = count($groupUrl, COUNT_RECURSIVE) - count($groupUrl);
        $nbSitesChecked = 0;
        foreach ($groupUrl as $sites) {
            foreach ($sites as $pages) {
                foreach ($pages as $page => $conf)
                {
                    wpccUtils::progress($nbSitesChecked, $nbSites, $this->_time);
                    $cleanUrl = wpccUtils::urlToString($page);
                    $fileName = $this->_contentCacheDir . $cleanUrl . '.php';
                    $errorFileName = $this->_errorCacheDir . $cleanUrl . '.php';
                    try {
                        $response = wpccFile::getContentFromFile($fileName, false);
                        $errorResponse = wpccFile::getContentFromFile($errorFileName, false);
                        if (false === $response && false === $errorResponse) {
                            $page = str_replace('&amp;', '&', $page);
                            self::generatePageCache($page, $fileName, $cleanUrl, $type);
                            $this->makeScreenshot($type, $cleanUrl, $page);
                        }
                    } catch (Exception $e) {
                        wpccFile::writeToFile($errorFileName, $e->getMessage()); // Request failed Saving error log
                    }
                    $nbSitesChecked++;
                }
            }
        }
    }



    /**
     * This function make a screeshot from a given url
     *
     * @param $type
     * @param $cleanUrl
     * @param $page
     */
    public function makeScreenshot($type, $cleanUrl, $page)
    {
        if (('all' === $type || 'screenshot' === $type)
            && (FALSE === strpos($page, self::$excludeScrenShot))) {
            $screenShotFilename = $this->_screenshotDir . $cleanUrl;
            exec("pageres " . $page . " " . self::$desktopFormat .
                "  --filename " . $screenShotFilename . self::$desktopFormat);
            wpccPngToJpg::compress($this->_screenshotDir, $this->_thumbnailDir,
                $cleanUrl . self::$desktopFormat);
        }
    }

}

?>