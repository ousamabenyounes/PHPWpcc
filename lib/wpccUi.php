<?php
require('wpcc.php');
require('wpccConfig.php');
require('wpccConfigLog.php');

class wpccUi extends wpcc
{
    protected $_rootDir;
    protected $_servicesConfig;
    protected $_twig;
    protected $_servicesNbFilesConfig;

    public static $projectName = 'phpwpcc';

    /**
     * @param array $webParsingConfig
     * @param int $servicesNbFilesConfig
     */
    public function __construct($root_dir, $webParsingConfig = array(), $servicesNbFilesConfig = array())
    {
        $this->_rootDir = $root_dir;
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem($root_dir . 'views');
        $this->_twig = new Twig_Environment($loader);

        $this->_servicesConfig = $webParsingConfig;
        $this->_servicesNbFilesConfig = $servicesNbFilesConfig;
    }


    /*
     * This function load the main form to configure your wpcc instance
    */
    public function configureProjectForm()
    {
        $template = $this->_twig->loadTemplate('install/configureProjectForm.tpl');
        echo $template->render(array());
    }


    /*
     * This function load the main form to configure your wpcc instance
     */
    public function configureProjectGenerate()
    {
        $template = $this->_twig->loadTemplate('php/phpwpcc_config.php.tpl');
        $phpTemplate = $template->render(
            array(
                'projectName' => $_POST["projectName"],
                'webServiceUrl' => $_POST["webServiceUrl"],
                'emailAdressList' => $this->textareaToArray($_POST["emailAdressList"])
            )
        );
        wpccConfig::save($this->_rootDir, $phpTemplate, 'wpcc_config');
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
        $template = $this->_twig->loadTemplate('install/configureServicesFormStep2.tpl');
        echo $template->render(
            array(
                'services' => $this->_servicesConfig,
                'servicesNbFilesConfig' => $this->_servicesNbFilesConfig
            )
        );
    }


    /**
     * This function generte
     */
    public function configureServicesGenerate() {
        try {
            $template = $this->_twig->loadTemplate('php/phpwpcc_services.php.tpl');
            $phpwpcc_service_config = $template->render(array(
                'post' =>  $_POST,
            ));
            wpccConfig::save($this->_rootDir, $phpwpcc_service_config, 'wpcc_services');



            die;
            $output = file_get_contents("http://ftven.jecodedoncjeteste.fr/wpccGenerate.php");

            $template = $this->_twig->loadTemplate('phpwpcc_generate.tpl');
            echo $template->render(array(
                    'generate_message' =>  $output,
                ));

        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }


    /*
     *
     */
    public function updateForm()
    {
        $template = $this->_twig->loadTemplate('phpwpcc_update.tpl');
        echo $template->render(
            array(
                'services' => $this->_servicesConfig,
            )
        );
    }

}

?>