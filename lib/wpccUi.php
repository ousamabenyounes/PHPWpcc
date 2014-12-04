<?php
require('wpcc.php');

class wpccUi extends wpcc
{
    protected $_webParsingConfig;
    protected $_twig;
    protected $_nbFileConfig;

    public static $projectName = 'phpwpcc';

    /**
     * @param array $webParsingConfig
     * @param int $nbFileConfig
     */
    public function __construct($webParsingConfig = array(), $nbFileConfig = array())
    {
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem(self::$root_dir . 'views');
        $this->_twig = new Twig_Environment($loader);

        $this->_webParsingConfig = $webParsingConfig;
        $this->_nbFileConfig = $nbFileConfig;
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
    public function configureProjectGenerate($data)
    {
        $template = $this->_twig->loadTemplate('php/phpwpcc_config.php.tpl');
        $phpTemplate = $template->render(
            array(
                'projectName' => $_POST["projectName"],
                'webServiceUrl' => $_POST["webServiceUrl"],
                'emailAdressList' => $this->textareaToArray($_POST["emailAdressList"])
            )
        );
        $this->writeToFile(self::$root_dir . 'config/wpcc_config.php', $phpTemplate);
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
                'services' => $this->_webParsingConfig,
                'nbFileConfig' => $this->_nbFileConfig
            )
        );
    }

    /*
     *
     */
    public function updateForm()
    {
        $template = $this->_twig->loadTemplate('phpwpcc_update.tpl');
        echo $template->render(
            array(
                'services' => $this->_webParsingConfig,
            )
        );
    }


}

?>