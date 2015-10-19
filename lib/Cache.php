<?php

namespace Phpwpcc;

use Symfony\Component\Filesystem\Filesystem ;

class Cache
{
    public static $cacheDir = 'cache/';
    public static $currentCache = 'current/';
    public static $contentPath = 'content/';
    public static $screenshotPath = 'screenshot/';
    public static $thumbnailPath = 'thumbnail/';
    public static $errorPath = 'error/';
    public static $cacheExt = '.php';
    public static $noPreviewAvailable = 'images/no-preview.jpg';
    public static $desktopFormat = '600X800';

    const INFO_TWIG_FILE = 'php/phpunit/info.php.twig';

    protected $_rootDir;
    protected $_cacheDir;
    protected $_contentCacheDir;
    protected $_screenshotDir;
    protected $_thumbnailDir;
    protected $_errorCacheDir;
    protected $_time;

    protected $fs;

    /**
     * @param string $root_dir
     * @param mixed  $fs
     */
    public function __construct($root_dir, $fs = null)
    {
        $this->_rootDir = $root_dir;
        $this->_time = time();
	if ($fs === null)
	{
	   $fs = new Filesystem();
	}
	$this->setFs($fs);
        $this->initCacheDir();
    }

    /**
     * fileSystem component setter
     * @param mixed $fs
     */
    private function setFs($fs)
    {
	$this->fs = $fs;
    }

    /**
     *
     * @param string $directory
     * @param int    $mode
     */
    private function mkdir($directory, $mode = 0777)
    {
	$this->fs->mkdir($directory, $mode);
    }

    /**
     * @return string $_contentCacheDir
     */
    public function getContentCacheDir()
    {
        return $this->_contentCacheDir;
    }


    public function purge() {
	if (file_exists($this->_cacheDir . 'info.php')) {
           require ($this->_cacheDir . 'info.php'); // Get datetime for the previous cache directory
           $purgeConfig = Config::getConfigArray('purge', 'purgeConfig', $this->_rootDir);
           $cachePurge = (int) Config::getVarFromConfig('cachePurge', $this->_rootDir);
           while ($cachePurge <= sizeof($purgeConfig['wpcc_cache'])) {
           	  $oldCacheDir = array_shift($purgeConfig['wpcc_cache']);
            	  Utils::execCmd('rm -rf ' . $this->_rootDir . self::$cacheDir  . $oldCacheDir . '/');
           }
           $purgeConfig['wpcc_cache'][] = $datetime;
           Twig::saveFileToTpl(
              'purge/update.php.tpl',
              array(
                'purgeConfig' => var_export($purgeConfig, true)
              ),
              $this->_rootDir . 'config/wpcc_purge.php',
              $this->_rootDir
            );
          Utils::execCmd('mv ' . $this->_cacheDir . ' '. $this->_rootDir . self::$cacheDir . $datetime);
	}

    }

    /**
     * this method create cache directories (screenshot, content...)
     */
    public function initCacheDir()
    {
        $oldmask = umask(0);
        $this->_cacheDir = $this->_rootDir . self::$cacheDir . self::$currentCache ;
        $this->_contentCacheDir = $this->_cacheDir . self::$contentPath;
        $this->_screenshotDir = $this->_cacheDir . self::$screenshotPath;
        $this->_thumbnailDir = $this->_screenshotDir . self::$thumbnailPath;;
        $this->_errorCacheDir = $this->_cacheDir . self::$errorPath;
        $this->purge();

	$this->mkdir($this->_rootDir . self::$cacheDir);
        $this->mkdir($this->_cacheDir);
        $this->mkdir($this->_contentCacheDir);
        $this->mkdir($this->_screenshotDir);
        $this->mkdir($this->_thumbnailDir);
        $this->mkdir($this->_errorCacheDir);
	Twig::saveFileToTpl(
			static::INFO_TWIG_FILE, 
			array('dateOfTest' =>  date("YmdHis")), 
			$this->_cacheDir . 'info.php',
			$this->_rootDir
	);
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
        if ('screenshotRefresh' === $type)
        {
            echo " - reGenerating screenshots \r\n";
        }
    }


    /**
     * @param $page
     * @param $fileName
     * @param $cleanUrl
     * @param string $type
     */
    public static function generatePageCache($page, $fileName, $type = 'all')
    {
        $response = wRequest::sendRequest($page);
        if ('all' === $type || 'content' === $type) {
            File::writeToFile($fileName, $response);
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
                    Utils::progress($nbSitesChecked, $nbSites, $this->_time);
                    $cleanUrl = Utils::urlToString($page);
                    $fileName = $this->_contentCacheDir . $cleanUrl . self::$cacheExt;
                    $errorFileName = $this->_errorCacheDir . $cleanUrl . self::$cacheExt;
                    try {
                        $response = File::getContentFromFile($fileName);
                        $errorResponse = File::getContentFromFile($errorFileName);			
                        if (false === $response && false === $errorResponse) {
                            $page = str_replace('&amp;', '&', $page);
                            self::generatePageCache($page, $fileName, $type);
                            $this->makeScreenshot($type, $cleanUrl, $page);
                        }
                        if ('screenshotRefresh' === $type) {
                                $page = str_replace('&amp;', '&', $page);
                                $this->makeScreenshot($type, $cleanUrl, $page);
                        }
                    } catch (\Exception $e) {
                        File::writeToFile($errorFileName, $e->getMessage()); // Request failed Saving error log
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
        if (('all' === $type || 'screenshot' === $type || 'screenshotRefresh' === $type)) {
            $screenShotFilename = $this->_screenshotDir . $cleanUrl;
            Utils::execCmd("pageres " . $page . " " . self::$desktopFormat .
                "  --filename " . $screenShotFilename . self::$desktopFormat);
            PngToJpg::compress($this->_screenshotDir, $this->_thumbnailDir,
                $cleanUrl . self::$desktopFormat);
        }
    }

}

