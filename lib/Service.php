<?php

namespace Phpwpcc;

class Service
{
    protected $_servicesConfig;
    protected $_servicesNbFilesConfig;
    protected $_rootDir;
    protected $_noCache;
    protected $_idSite;
    protected $_time;
    protected $_htmlCache;
    protected $_cacheDir;
    protected $_contentCacheDir;
    protected $_errorCacheDir;
    protected $_checkObj;
    protected static $api_url =		'http://api.lereferentiel.francetv.fr/archimade/';
    protected static $json_url = 	'http://api.lereferentiel.francetv.fr/sites/';
    protected static $group_url_file =  'php/phpwpcc_groupurl.php.tpl';

    const PAGE = 0;
    const PAGE_NOCACHE = 1;
    const API = 2;
    const API_NOCACHE = 3;
    const PROJECTNAME = 0;
    const ACTIVATEDSERVICES = 1;
    const SERVICECONFIGURATION = 2;
    const STRIPSLASH = 3;




  /**
     * @param array $servicesConfig
     * @param int   $servicesNbFilesConfig
     */
    public function __construct($servicesConfig = array(), $servicesNbFilesConfig = array())
    {
        $this->_servicesConfig = $servicesConfig;
        $this->_servicesNbFilesConfig = $servicesNbFilesConfig;
    }

    /*
     * This function load the service configuration form
     */
    public function configureServicesForm()
    {
        echo Twig::getTemplateContent(
            'install/configureServicesForm.tpl',
            array()
        );
    }


    /*
     * This function generate the service php config file
     */
    public function configureServicesFormStep2()
    {
        echo Twig::getTemplateContent(
            'install/configureServicesFormStep2.tpl',
            array(
                'services' => $this->_servicesConfig,
                'servicesNbFilesConfig' => $this->_servicesNbFilesConfig
            )
        );
    }

    /**
     * This function allow you to update services configuration
     */
    public function updateServiceForm($groupUrl)
    {
        echo Twig::getTemplateContent(
            'services/update.tpl',
            array(
                'groupUrl' => $groupUrl,
                'services' => $this->_servicesConfig,
            )
        );
    }

    /**
     * This function generte
     */
    public function updateService()
    {
        try {
            $phpwpcc_service_config = Twig::getTemplateContent(
                'php/phpwpcc_services.php.tpl',
                array(
                    'post' => $_POST,
                )
            );
            Config::save($phpwpcc_service_config, 'wpcc_services');
        } catch (\Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }


    /**
     * This function print the form that allow url to services attachment
     *
     * @param array $groupUrl
     * @param string $service
     * @param array $servicesConfig
     */
    public function attachUrlWithServices($groupUrl, $service, $servicesConfig)
    {
        echo Twig::getTemplateContent(
            'services/attachWithUrl.tpl',
            array(
                'groupUrl' => $groupUrl,
                'service' => $service,
                'services' => $servicesConfig
            )
        );
    }


    /**
     * This function generate the service configuration file
     *
     * @param array  $choosenUrls
     * @param string $service
     * @param array  $groupUrl
     */
    public function attachUrlWithServicesGenerate($choosenUrls, $service, $groupUrl)
    {
        foreach ($groupUrl as $portail => $sites) {
            foreach ($sites as $webSite => $urls) {
                foreach ($urls as $url => $urlConfig) {
                    if (in_array($url, $choosenUrls)){
                        if (!in_array($service, $urlConfig)) {
                            $urlConfig[] = $service;
                        }
                    } else {
                        $urlConfig = array_diff($urlConfig, array($service));
                    }
                    $groupUrl[$portail][$webSite][$url] = $urlConfig;
                }
            }
        }
        $groupUrlContent = Twig::getTemplateContent(
            'php/phpwpcc_groupurl_attach_service.php.tpl',
            array(
                'groupUrl' => $groupUrl,
            )
        );
        Config::save($groupUrlContent, 'wpcc_groupurl');
    }

}
