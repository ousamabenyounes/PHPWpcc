<?php
require('localVars.php');
require($root_dir . 'vendor/autoload.php');
require($root_dir . 'config/wpcc_services.php');
require($root_dir . 'config/wpcc_config.php');
require($root_dir . 'lib/wpccFile.php');
require($root_dir . 'lib/wpccConfig.php');
require($root_dir . 'lib/wpccConfigLog.php');
require($root_dir . 'lib/wpccRequest.php');
require($root_dir . 'lib/wpccUtils.php');
require($root_dir . 'lib/wpccTwig.php');
require($root_dir . 'lib/wpccCache.php');

class GroupUrl
{
    protected $_rootDir;
    protected $_noCache;
    protected $_idSite;
    protected $_time;
    protected $_htmlCache;
    protected $_cacheDir;
    protected $_contentCacheDir;
    protected $_errorCacheDir;
    protected $_checkObj;
    protected static $api_url = "http://api.lereferentiel.francetv.fr/archimade/";
    protected static $json_url = "http://api.lereferentiel.francetv.fr/sites/";
    protected static $group_url_file = 'php/phpwpcc_groupurl.php.tpl';

    const PAGE = 0;
    const PAGE_NOCACHE = 1;
    const API = 2;
    const API_NOCACHE = 3;
    const PROJECTNAME = 0;
    const ACTIVATEDSERVICES = 1;
    const SERVICECONFIGURATION = 2;
    const STRIPSLASH = 3;


    /**
     * @param string $root_dir
     */
    public function __construct($root_dir)
    {
        $this->_rootDir = $root_dir;
        $this->_time = time();
        $this->_noCache = date("Ymd_H");
        $this->_cacheDir = $root_dir . wpccCache::$cacheDir . wpccCache::$currentCache . '/';
        $this->_contentCacheDir = $this->_cacheDir . 'content/';
        $this->_errorCacheDir = $this->_cacheDir . 'error/';
    }


    /**loadTemplate
     * This function parse the html content and returns two meta informations
     *
     * @return array|boolean $archimadeConfig config or false if site isn't connected to archimade
     */
    protected function getIdSiteAndToken()
    {
        $doc = new DOMDocument();
        @$doc->loadHTML($this->_htmlCache);
        $metas = $doc->getElementsByTagName('meta');
        for ($i = 0; $i < $metas->length; $i++) {
            $meta = $metas->item($i);
            if ($meta->getAttribute('name') == 'archimade_idsite') {
                $idSite = $meta->getAttribute('content');
            }
            if ($meta->getAttribute('name') == 'archimade_token') {
                $token = $meta->getAttribute('content');
            }
        }
        if (!isset($idSite)) {
            return false;
        }
        $archimadeConfig = array(
            'idSite' => $idSite,
            'token' => $token
        );

        return $archimadeConfig;
    }


    protected function getPageServiceConfig($page, $serviceInitConfig)
    {

        $cleanUrl = wpccUtils::urlToString($page);
        $fileName = $this->_contentCacheDir . $cleanUrl . '.php';
        $response = wpccFile::getContentFromFile($fileName, false);
        if (false !== $response) {
            $this->_htmlCache = $response;
        } else {
            $this->_htmlCache = wpccRequest::sendRequest($page);
            wpccCache::generatePageCache($page, $fileName, $cleanUrl);
        }
        if (isset($serviceInitConfig[self::STRIPSLASH])) {
            $this->_htmlCache = stripslashes($this->_htmlCache);
        }
        return $this->getServiceInitConfiguration($serviceInitConfig);
    }


    /**
     * Get all FTV pages configuration (with activated services)
     *
     * @param $page
     * @param $serviceInitConfig
     * @return array
     */
    protected function getFtvPages($page, $serviceInitConfig)
    {
        $serviceInitConf = array();
        $serviceInitConf[self::PAGE] = $this->getPageServiceConfig(
            $page,
            $serviceInitConfig
        );
        $archimadeConfig = $this->getIdSiteAndToken();
        $serviceInitConf[self::PAGE_NOCACHE] = $this->getPageServiceConfig(
            $page . '?dt=' . $this->_noCache,
            $serviceInitConfig
        );
        //  ************ Site not connected with archimade ************//
        if (false === $archimadeConfig) {
            return array(
                $page => $serviceInitConf[self::PAGE],
                $page . '?dt=' . $this->_noCache => $serviceInitConf[self::PAGE_NOCACHE],
            );
        }
        //  ************ Site connected with archimade *****************//
        $this->_idSite = $archimadeConfig['idSite'];
        $token = $archimadeConfig['token'];
        $archimadeString = '?id_site=' . $this->_idSite . ($token != '' ? '&token=' . $token : '');
        $serviceInitConfig[self::STRIPSLASH] = true;
        $serviceInitConf[self::API] = $this->getPageServiceConfig(
            self::$api_url . $archimadeString,
            $serviceInitConfig
        );
        $serviceInitConf[self::API_NOCACHE] = $this->getPageServiceConfig(
            self::$api_url . $archimadeString . '&dt=' . $this->_noCache,
            $serviceInitConfig
        );
        return array(
            $page => $serviceInitConf[self::PAGE],
            $page . '?dt=' . $this->_noCache => $serviceInitConf[self::PAGE_NOCACHE],
            self::$api_url . $archimadeString => $serviceInitConf[self::API],
            self::$api_url . $archimadeString . '&dt=' . $this->_noCache => $serviceInitConf[self::API_NOCACHE],
        );
    }


    /**
     * This function return the list of activated services on current page
     *
     * @param boolean $initConfiguration
     * @param array $servicesConfig
     *
     * @return array $activedServices
     */
    protected function getServiceInitConfiguration($serviceInitConfig)
    {
        $activedServices = array();
        $servicesConfig = $serviceInitConfig[self::SERVICECONFIGURATION];
        foreach ($servicesConfig as $serviceName => $config) {
            $checkFct = $serviceInitConfig[self::PROJECTNAME]. 'Check' . $serviceName . 'Present';
            if (true === $this->_checkObj->$checkFct($this->_htmlCache))
            {
                $activedServices[] = $serviceName;
            }
        }
        return $activedServices;
    }


    /**
     * This function generate all FTV urls configuration
     * portails => websites => pages
     *
     * @param array $serviceInitConfig
     */
    public function generateAllUrlConfig($serviceInitConfig = array())
    {
        $json = file_get_contents(self::$json_url);
        $sites = json_decode($json, true);
        $groupUrl = array();
        $nbSites = count($sites);
        $nbSitesChecked = 0;

        $projectName = $serviceInitConfig[self::PROJECTNAME];
        $checkObjClass = $projectName . 'CheckServicesPresent';
        require ($this->_rootDir . 'phpunitTests/lib/' . $checkObjClass . '.php');
        $this->_checkObj = new $checkObjClass();
        foreach ($sites as $site) {
            if ('En ligne' === $site['etat'] && "" !== trim($site["portail"])
                && !strstr($site['url'], 'programmes.france')
                && !strstr($site['url'], '-preprod')
            ) {
                wpccUtils::progress($nbSitesChecked, $nbSites, $this->_time);
                $site['url'] = trim(rtrim($site['url'], "/"));
                try {
                    $pages = $this->getFtvPages($site['url'], $serviceInitConfig);
                } catch (Exception $e) {
                    continue;
                }
                /*foreach ($tokens as $token) {
                    $tokenPages = $this->getFtvPages($site["url"], false, array(), $token);
                    if (false !== $tokenPages && false !== $pages) {
                        $pages = array_merge($pages, $tokenPages);
                    } elseif (false === $pages) {
                        $pages = $tokenPages;
                    }
                }*/
                $groupUrl[$site["portail"]][] = array($site["url"] => $pages);
            }
            $nbSitesChecked++;
        }
        $tplConf = array('groupUrl' => $groupUrl);
        wpccTwig::saveConfigToTpl(self::$group_url_file, $tplConf, 'wpcc_groupurl', $this->_rootDir);
    }


}


try {
    $groupurl = new GroupUrl($root_dir);
    //$groupurl->generateAllUrlConfig();
    $serviceInitConfig = array();
    $serviceInitConfig[GroupUrl::PROJECTNAME] = $phpwpcc_config['projectName'];
    $serviceInitConfig[GroupUrl::SERVICECONFIGURATION] = $servicesConfig;
    $groupurl->generateAllUrlConfig($serviceInitConfig);
} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}



?>