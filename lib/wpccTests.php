<?php
require('wpcc.php');
require('wpccConfig.php');
require('wpccConfigLog.php');
require('wpccUtils.php');

class wpccTests extends wpcc
{
    protected $_rootDir;
    protected $_testPath;
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

        $this->_testPath = $this->_rootDir . 'generatedTest/phpunitTests/';
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
    public function generateAllTestsByPagesMethodsContent($projectName, $services, $groupUrl)
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
                }
            }
        }
        $this->generateAllTestsByPagesMethodsFiles($projectName);
    }


    /**
     * This function generate all PhpUnit test files by service
     *
     * @param string $projectName
     */
    public function generateAllTestsByPagesMethodsFiles($projectName) {

        foreach ($this->_testFiles as $service => $content) {
            $testFileName = UCFirst($projectName) . 'Check' . UCFirst($service) . '.php';
            wpccFile::writeToFile($this->_testPath . $testFileName , $content);
        }

    }


    /**
     * This function generate the main PhpWpcc Test Class
     *
     * @param string $projectName
     */
    public function generateMainTestsClass($projectName) {
        $template = $this->_twig->loadTemplate('php/phpunit/phpwpcc_main_class.php.tpl');
        $phpwpccMainClass = $template->render(array(
            'projectName' => $projectName
        ));
        wpccFile::writeToFile($this->_testPath . UCFirst($projectName) . 'Check.php', $phpwpccMainClass);
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
        $this->generateMainTestsClass($projectName);
        $this->generateAllTestsCheckMethods($projectName, $services);
        $this->generateAllTestsByPagesMethodsContent($projectName, $services, $groupUrl);
    }





}
?>