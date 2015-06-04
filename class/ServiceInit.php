<?php
namespace Wpcc;

class ServiceInit
{
    protected $_rootDir;
    protected $_idSite;
    protected $_time;
    protected $_htmlCache;
    protected $_cacheDir;
    protected $_contentCacheDir;
    protected $_errorCacheDir;
    protected $_checkObj;
    protected static $group_url_file = 'php/phpwpcc_groupurl.php.tpl';

    const PAGE = 0;
    const PAGE_NOCACHE = 1;
    const PROJECTNAME = 0;
    const ACTIVATEDSERVICES = 1;
    const SERVICECONFIGURATION = 2;
    const STRIPSLASH = 3;

    /**
     * @param string $root_dir
     */
    public function serviceInitBegin($root_dir)
    {
        $this->_rootDir = $root_dir;
        $this->_time = time();
        $this->_cacheDir = $root_dir . Cache::$cacheDir . Cache::$currentCache . '/';
        $this->_contentCacheDir = $this->_cacheDir . 'content/';
        $this->_errorCacheDir = $this->_cacheDir . 'error/';
    }


    protected function getPageServiceConfig($page, $serviceInitConfig)
    {
        $cleanUrl = Utils::urlToString($page);
        $fileName = $this->_contentCacheDir . $cleanUrl . '.php';
        $response = File::getContentFromFile($fileName, false);
        if (false !== $response) {
            $this->_htmlCache = $response;
        } else {
            $this->_htmlCache = wRequest::sendRequest($page);
            Cache::generatePageCache($page, $fileName, $cleanUrl);
        }
        if (isset($serviceInitConfig[self::STRIPSLASH])) {
            $this->_htmlCache = stripslashes($this->_htmlCache);
        }
        return $this->getServiceInitConfiguration($serviceInitConfig);
    }


    /**
     * Get all pages configuration (with activated services)
     *
     * @param $page
     * @param $serviceInitConfig
     *
     * @return array
    */
    protected function getPages($page, $serviceInitConfig)
    {
        return array(
                $page => $this->getPageServiceConfig(
				$page,
				$serviceInitConfig
	    	)
        );
    }


    /**
     * This function return the list of activated services on current page
     *
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
     * This function generate all urls configuration
     * portails => websites => pages
     *
     * @param array $serviceInitConfig
     * @param array $groupUrl
     */
    public function generateAllUrlConfig($serviceInitConfig = array(), $groupUrl)
    {
        $projectName = $serviceInitConfig[self::PROJECTNAME];
        $checkObjClass = $projectName . 'CheckServicesPresent';	
        require ($this->_rootDir . 'phpunitTests/lib/' . $checkObjClass . '.php');
        $this->_checkObj = new $checkObjClass();
        foreach ($groupUrl as $portail => $sites) 
	{
	 foreach ($sites as $site => $pages) 
	 {
	  foreach ($pages as $page => $services) 
	  {
                try {
                    $pages = $this->getPages($page, $serviceInitConfig);
                } catch (\Exception $e) {
                    continue;
                }
                $groupUrl[$portail][$page] = array($site => $pages);
	  }
	 }
        }
        $tplConf = array('groupUrl' => $groupUrl);
        Twig::saveConfigToTpl(self::$group_url_file, $tplConf, 'wpcc_groupurl', $this->_rootDir);
    }

}
