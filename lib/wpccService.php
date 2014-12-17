<?php
require('wpcc.php');
require('wpccConfig.php');
require('wpccConfigLog.php');

class wpccService extends wpcc
{
    protected $_rootDir;
    protected $_servicesConfig;
    protected $_twig;
    protected $_servicesNbFilesConfig;

    /**
     * @param array $servicesConfig
     * @param int $servicesNbFilesConfig
     */
    public function __construct($root_dir, $servicesConfig = array(), $servicesNbFilesConfig = array())
    {
        $this->_rootDir = $root_dir;
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem($root_dir . 'views');
        $this->_twig = new Twig_Environment($loader, array('debug' => true));
        $this->_twig->addExtension(new Twig_Extension_Debug());

        $this->_servicesConfig = $servicesConfig;
        $this->_servicesNbFilesConfig = $servicesNbFilesConfig;
    }

    /*
     * This function load the service configuration form
     */
    public function configureServicesForm()
    {
        $template = $this->_twig->loadTemplate('install/configureServicesForm.tpl');
        echo $template->render(array());
    }


    /*
     * This function generate the service php config file
     */
    public function configureServicesFormStep2()
    {
        echo wpccTwig::getTemplateContent(
            'install/configureServicesFormStep2.tpl',
            array(
                'services' => $this->_servicesConfig,
                'servicesNbFilesConfig' => $this->_servicesNbFilesConfig
            ),
            $this->_rootDir
        );
    }


    /**
     * This function allow you to update services configuration
     */
    public function updateService()
    {

        $template = $this->_twig->loadTemplate('services/phpwpcc_update.tpl');
        echo $template->render(
            array(
                'groupUrl' => $groupUrl,
                'services' => $this->_servicesConfig,
            )
        );


    }

    /**
     * This function generte
     */
    public function configureServicesGenerate()
    {
        try {
            $template = $this->_twig->loadTemplate('php/phpwpcc_services.php.tpl');
            $phpwpcc_service_config = $template->render(array(
                'post' => $_POST,
            ));
            wpccConfig::save($this->_rootDir, $phpwpcc_service_config, 'wpcc_services');


            die;
            $output = file_get_contents("http://ftven.jecodedoncjeteste.fr/wpccGenerate.php");

            $template = $this->_twig->loadTemplate('phpwpcc_generate.tpl');
            echo $template->render(array(
                'generate_message' => $output,
            ));

        } catch (Exception $e) {
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
        echo wpccTwig::getTemplateContent(
            'install/attachUrlWithServices.tpl',
            array(
                'groupUrl' => $groupUrl,
                'service' => $service,
                'services' => $servicesConfig
            ),
            $this->_rootDir
        );
    }


    /**
     * This function generate the service configuration file
     *
     * @param string $projectName
     * @param array $servicesConfig
     * @param array $choosenUrls
     * @param string $service
     */
    public function attachUrlWithServicesGenerate($projectName, $servicesConfig, $choosenUrls, $service)
    {
        global $groupUrl;

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

        $template = $this->_twig->loadTemplate('php/phpwpcc_groupurl_attach_service.php.tpl');
        $groupUrlContent = $template->render(array(
            'groupUrl' =>  $groupUrl
        ));

        wpccConfig::save($this->_rootDir, $groupUrlContent, 'wpcc_groupurl');


       /* die;





        $template = $this->_twig->loadTemplate('php/phpunit/phpwpcc_main_class.php.tpl');
        $phpwpccMainClass = $template->render(array(
                'projectName' => $projectName
            ));
        wpccFile::writeToFile($this->_rootDir . 'generatedTest/' . $projectName . 'Check.php', $phpwpccMainClass);
*/
    }





}
?>