<?php
require('wpcc.php');
require('wpccConfig.php');
require('wpccConfigLog.php');
require('wpccUtils.php');

class wpccTests extends wpcc
{
    protected $_rootDir;
    protected $_servicesConfig;
    protected $_twig;
    protected $_testFiles;


    /**
     * @param array $servicesConfig
     * @param int $servicesNbFilesConfig
     */
    public function __construct($root_dir, $servicesConfig = array())
    {
        $this->_rootDir = $root_dir;
        $this->_testFiles = array();
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem($root_dir . 'views');
        $this->_twig = new Twig_Environment($loader, array('debug' => true));
        $this->_twig->addExtension(new Twig_Extension_Debug());

        $this->_servicesConfig = $servicesConfig;
    }


    /**
     * This function generate all check methods for all services
     *
     * @param string $projectName
     * @param array $services
     */
    public function generateAllTestsCheckMethods($projectName, $services)
    {
        foreach ($services as $service => $serviceConf)
        {
            foreach ($serviceConf['acceptedConfig'] as $confName => $files)
            {
                $template = $this->_twig->loadTemplate('php/phpunit/phpwpcc_test_main_class.php.tpl');
                $serviceMainClass = $template->render(array(
                            'projectName' => $projectName,
                            'service' => $service,
                            'acceptedConfig' => $serviceConf['acceptedConfig']
                        )
                );
                $this->_testFiles[strtolower($service)] = $serviceMainClass;
            }
        }
     }


    /**
     * This function generate all check methods for all pages
     *
     * @param string $projectName
     * @param array $services
     * @param array $groupUrl
     */
    public function generateAllTestsByPagesMethods($projectName, $services, $groupUrl)
    {
        foreach ($groupUrl as $portail => $sites) {
            foreach ($sites as $webSite => $pages) {
                foreach ($pages as $page => $pageConfig) {
                    if (0 !== sizeof($pageConfig)){
                        $urlCleaned = wpccUtils::getDomainWithoutExtention($page);
                        foreach ($pageConfig as $activedService) {
                            $phpunitTestFunctionName = 'testIf' . UCFirst($urlCleaned) .
                                'Contains' . UCFirst($activedService);
                            $checkMethod = UCFirst($projectName) . 'Check' . UCFirst($activedService);
                            $template = $this->_twig->loadTemplate('php/phpunit/phpwpcc_test_content_class.php.tpl');
                            $serviceAllTestsByPageContent = $template->render(array(
                                    'phpunitTestFunctionName' => $phpunitTestFunctionName,
                                    'page' => $page,
                                    'checkMethod' => $checkMethod
                                )
                            );
                            $this->_testFiles[strtolower($activedService)] .= $serviceAllTestsByPageContent;

                        }
                    }
                    /*    foreach ($services as $service => $serviceConf) {
                            var_dump($service);
                            die;
                        }
    */
                }
            }
        }
        var_dump($this->_testFiles);
        die;
    }

    /**
     * This function generate all Phpunit Tests
     *
     * @param string $projectName
     * @param array $services
     * @param array $groupUrl
     */
    public function generateAllTests($projectName, $services, $groupUrl)
    {
        $this->generateAllTestsCheckMethods($projectName, $services);
        $this->generateAllTestsByPagesMethods($projectName, $services, $groupUrl);





       /* die;

 $template = $this->_twig->loadTemplate('php/phpwpcc_groupurl_attach_service.php.tpl');
        $groupUrlContent = $template->render(array(
            'groupUrl' =>  $groupUrl
        ));

        wpccConfig::save($this->_rootDir, $groupUrlContent, 'wpcc_groupurl');



        $template = $this->_twig->loadTemplate('php/phpunit/phpwpcc_main_class.php.tpl');
        $phpwpccMainClass = $template->render(array(
                'projectName' => $projectName
            ));
        wpccFile::writeToFile($this->_rootDir . 'generatedTest/' . $projectName . 'Check.php', $phpwpccMainClass);
*/
    }





}
?>