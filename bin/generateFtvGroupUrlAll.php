<?php
require('localVars.php');
require($root_dir . 'vendor/autoload.php');
require($root_dir . 'lib/wpccFile.php');
require($root_dir . 'lib/wpccConfig.php');
require($root_dir . 'lib/wpccConfigLog.php');
require($root_dir . 'lib/wpccRequest.php');
require($root_dir . 'lib/wpccUtils.php');
require($root_dir . 'lib/wpccTwig.php');
require($root_dir . 'config/wpcc_services.php');

class GroupUrl
{
    protected $_root;
    protected $_noCache;
    protected $_idSite;
    protected $_time;
    protected $_htmlCache;
    protected static $api_url = "http://api.lereferentiel.francetv.fr/archimade/";
    protected static $json_url = "http://api.lereferentiel.francetv.fr/sites/";
    protected static $group_url_file = 'php/phpwpcc_groupurl.php.tpl';
    /**
     * @param string $root_dir
     */
    public function __construct($root_dir)
    {
        $this->_root = $root_dir;
        $this->_time = time();
        $this->_noCache = date("Ymd_H");
    }


    /**loadTemplate
     * This function parse the html content and returns two meta informations
     *
     * @param string $html
     * @return array|boolean $archimadeConfig config or false if site isn't connected to archimade
     */
    protected function getIdSiteAndToken($html)
    {
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
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


    /**
     * @param string $page
     * @param string $archimadeString
     * @return array
     */
    protected function getAllFtvUrlsFromPage($page, $archimadeString)
    {
        return array(
            $page,
            $page . '?dt=' . $this->_noCache,
            self::$api_url . $archimadeString,
            self::$api_url . $archimadeString . '&dt=' . $this->_noCache,
        );
    }

    /**
     * This function returns a list of FTV url from a given web page
     * => api cache / no cache + url cache / no cache
     *
     * @param string $page
     * @param boolean $parsePage
     * @param string $token (optionnal)
     *
     * @return array | boolean $pages
     */
    protected function getFtvPages($page, $parsePage = false, $token = '')
    {
        try {
            if (true === $parsePage) {
                $this->_htmlCache = wpccRequest::sendRequest($page);
                $archimadeConfig = $this->getIdSiteAndToken($this->_htmlCache);
                if (false === $archimadeConfig) {
                    return array(
                        $page,
                        $page . '?dt=' . $this->_noCache,
                    );
                }
                $this->_idSite = $archimadeConfig['idSite'];
                $token = $archimadeConfig['token'];
                $archimadeString = '?id_site=' . $this->_idSite . ($token != '' ? '&token=' . $token : '');
                return $this->getAllFtvUrlsFromPage($page, $archimadeString);
            }
            $archimadeString = '?idsite=' . $this->_idSite . '&token=' . $token;
            $page = $page . '/' . $token;
            return $this->getAllFtvUrlsFromPage($page, $archimadeString);
        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * This function return the list of activated services on current page
     *
     * @param boolean $initConfiguration
     * @param array $servicesConfig
     *
     * @return array $initConfiguratio
     */
    protected function initConfiguration($initConfiguration, $servicesConfig)
    {
        if (true === $initConfiguration) {
            foreach ($servicesConfig as $serviceName => $config) {
                foreach ($config['acceptedConfig'] as $version => $files) {
                    foreach ($files as $file) {
                        echo 'file'.$file;
                        die;
                    }
                }
            }
        }
        return array();
    }


    /**
     * This function generate all FTV urls configuration
     * portails => websites => pages
     *
     * @param boolean $initConfiguration
     */
    public function generateAllUrlConfig($initConfiguration = false, $servicesConfig = array())
    {
        $json = file_get_contents(self::$json_url);
        $sites = json_decode($json, true);
        $groupUrl = array();
        $tokens = array('video', 'programme-tv'); // here add token to each FTV webSites
        $nbSites = count($sites);
        $nbSitesChecked = 0;
        foreach ($sites as $site) {
            if ('En ligne' === $site['etat'] && "" !== trim($site["portail"])
                && !strstr($site['url'], 'programmes.france')
                && strstr($site['url'], 'pluzz') && !strstr($site['url'], '-preprod'))
                {
                wpccUtils::progress($nbSitesChecked, $nbSites, $this->_time);
                $site['url'] = trim(rtrim($site['url'], "/"));
                $pages = $this->getFtvPages($site['url'], true);
                $initServiceConfig = $this->initConfiguration($initConfiguration, $servicesConfig);
                foreach ($tokens as $token) {
                    $tokenPages = $this->getFtvPages($site["url"], false, $token);
                    if (false !== $tokenPages && false !== $pages) {
                        $pages = array_merge($pages, $tokenPages);
                    } elseif (false === $pages) {
                        $pages = $tokenPages;
                    }
                }
                $groupUrl[$site["portail"]][] = array($site["url"] => $pages);
            }
            $nbSitesChecked++;
        }
        $tplConf =  array('groupUrl' => $groupUrl);
        wpccTwig::saveConfigToTpl(self::$group_url_file, $tplConf, 'wpcc_groupurl', $this->_root);
    }


}



try {
    $groupurl = new GroupUrl($root_dir);
    $groupurl->generateAllUrlConfig(true, $servicesConfig);
} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}



?>