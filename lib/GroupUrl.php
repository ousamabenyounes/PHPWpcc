<?php

namespace Phpwpcc;

class GroupUrl
{
    protected $_groupUrl;

    /**
     * @param array $groupUrl
     * @param int   $servicesNbFilesConfig
     */
    public function __construct($groupUrl = array())
    {
        $this->_groupUrl = $groupUrl;
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
                'services' => $this->_groupUrl,
                'servicesNbFilesConfig' => $this->_servicesNbFilesConfig
            )
        );
    }

    /**
     * This function allow you to update services configuration
     */
    public function updateGroupUrlForm()
    {
        echo Twig::getTemplateContent(
            'groupurl/update.tpl',
            array(
                'groupurl' => $this->_groupUrl,
            )
        );
    }

    /**
     * Update all group urls 
     *
     * @param array $post
     */
    public function updateGroupUrl($post)
    {
        $groupUrl = array();
        $portails = $post['portails'];
        foreach ($portails as $portail) {
            $groupUrl[$portail] = array();
            $nbWebsites = $post['nbWebsites_' . $portail];
            for ($ind = 1; $ind <= $nbWebsites; $ind++) {
                $webSitekey = $post['website_' . $portail . '_' . $ind];
                $groupUrl[$portail][$webSitekey] = array();
                $pagesText = $post['pages_' . $portail . '_' . $ind . '_text'];
                $pages = explode("\r", $pagesText);
                foreach ($pages as $page) {
                    $page = trim($page, "\n");
		    if (strlen($page)) {  		       
                       if (isset($this->_groupUrl[$portail][$webSitekey][$page])) {
                       	   	$groupUrl[$portail][$webSitekey][$page] = $this->_groupUrl[$portail][$webSitekey][$page];
                    	   } else {
                             	$groupUrl[$portail][$webSitekey][$page] = array();
                    	   }
		    }
                }
            }
        }
        $groupUrlConfig = Twig::getTemplateContent(
            'php/phpwpcc_groupurl_update.php.tpl',
            array(
                'groupUrl' => var_export($groupUrl, true),
            )
        );
        Config::save($groupUrlConfig, 'wpcc_groupurl');
    }


    /**
     * This function print the form that allow url to services attachment
     *
     * @param array $groupUrl
     * @param string $service
     * @param array $groupUrl
     */
    public function attachUrlWithServices($groupUrl, $service, $groupUrl)
    {
        echo Twig::getTemplateContent(
            'services/attachWithUrl.tpl',
            array(
                'groupUrl' => $groupUrl,
                'service' => $service,
                'services' => $groupUrl
            )
        );
    }

}
