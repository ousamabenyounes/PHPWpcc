<?php

namespace Wpcc;

use Guzzle\Tests\GuzzleTestCase,
    Guzzle\Plugin\Mock\MockPlugin,
    Guzzle\Http\Message\Response,
    Guzzle\Http\Client as HttpClient,
    Guzzle\Service\Client as ServiceClient,
    Guzzle\Http\EntityBody;

require('localVars.php');
require($rootDir . 'autoload.php');


class ProjectTestCheck extends GuzzleTestCase
{
    protected $_client;
    protected static $_cache;
    protected static $_cacheContentDir;
    protected static $nbTests = 0;
    protected static $numTest = 0;
    protected static $testsOk = array();
    protected static $testsFailed = array();

    const STATUS_TPL = 'php/phpunit/status.php.tpl';
    const SERVICE_POS = 0;
    const PAGE_POS = 1;
    const FCT_NAME_POS = 2;
    const PORTAIL_POS = 3;

    /**
     * Clean tests array after running all tests from a class
     */
    protected function tearDown()
    {
        self::$testsOk = array();
        self::$testsFailed = array();
        File::writeToFile('info.php', '<?php   $datetime = "' . date("YmdHis") . '";');
    }


    /**
    * @param string $url
    * @group mainClass
    */
    protected function getHtmlContent($url) {
        if (!isset(self::$_cacheContentDir))    // move to phpunit init function !
        {
            self::$_cacheContentDir = '../' . Cache::$cacheDir . Cache::$currentCache .
                '/'. Cache::$contentPath;
        }
        $cacheFileName = self::$_cacheContentDir . Utils::urlToString($url) . '.php';
        $response = File::getContentFromFile($cacheFileName);
        if (false !== $response)
        {
            return stripslashes($response);
        } elseif (!isset(self::$_cache[$url])) {
           $client = new HttpClient($url);
           $request = $client->get();
           $response = $request->send();
           return stripslashes($response->getBody(true));
        }
    }

    /**
     * @param string $page
     * @group mainClass
     * @return string
    */
    protected static function getThumbnailFile($page)
    {
        global $rootDir;

        $cleanUrl = Utils::urlToString($page);
        $screenShotDir = Cache::$cacheDir . Cache::$currentCache .
            Cache::$screenshotPath ;
        $thumbnailDir = $screenShotDir . Cache::$thumbnailPath;
        $screenShotFile = $screenShotDir . $cleanUrl .
            Cache::$desktopFormat . PngToJpg::$picturesOutExt;
        $thumbnailFile = $thumbnailDir . $cleanUrl .
            Cache::$desktopFormat . PngToJpg::$miniExt . PngToJpg::$picturesOutExt;
        if (file_exists($rootDir . $thumbnailFile) && file_exists($rootDir . $screenShotFile)) {
            return (array($thumbnailFile, $screenShotFile));
        }
        return null;
    }


    /**
     * @param array $config
     * @param string $error
     */
    protected function nextTest($config, $type, $error = '')
    {
        $service = $config[self::SERVICE_POS];
        $page = $config[self::PAGE_POS];
        $fctName = $config[self::FCT_NAME_POS];
        $portail = $config[self::PORTAIL_POS];
        self::$nbTests++;
        $screenshotAndThumb = self::getThumbnailFile($page);
        if ('' === $error) {
            self::$testsOk[$fctName] = array($page);
            if (NULL !== $screenshotAndThumb) {
                array_push(self::$testsOk[$fctName], $screenshotAndThumb);
            }
        } else {
            self::$testsFailed[$fctName] = array($page, $error);
            if (NULL !== $screenshotAndThumb) {
                array_push(self::$testsFailed[$fctName], $screenshotAndThumb);
            }
        }
            $tplConfig = array(
                'testsOk' => var_export(self::$testsOk, true),
                'testsFailed' => var_export(self::$testsFailed, true),
		'info' =>  date("d-m-Y H:i:s"),
            );
            Twig::saveFileToTpl(
                self::STATUS_TPL,
                $tplConfig,
                '../' . Tests::TESTS_STATUS_DIR . '/' . UCFirst($service) . UCFirst($portail) . $type . '.php',
                '../'
            );
        self::$numTest++;
}

}
