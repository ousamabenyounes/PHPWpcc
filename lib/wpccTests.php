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
    protected $_template;

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
            $template = $this->_twig->loadTemplate('php/phpunit/phpwpcc_test_main_present_class.php.tpl');
            $serviceMainClass = $template->render(array(
                        'projectName' => $projectName,
                        'service' => $service,
                        'acceptedConfig' => $serviceConf['acceptedConfig']
                    )
            );
            $this->_testFiles['present'][strtolower($service)] = $serviceMainClass;

            $templateNotPresent = $this->_twig->loadTemplate('php/phpunit/phpwpcc_test_main_not_present_class.php.tpl');
            $serviceMainClass = $templateNotPresent->render(array(
                    'projectName' => $projectName,
                    'service' => $service,
                    'acceptedConfig' => $serviceConf['acceptedConfig']
                )
            );
            $this->_testFiles['notPresent'][strtolower($service)] = $serviceMainClass;
        }
     }

    /**
     * This function add function to all checkPresent CheckNotPresent files
     *
     * @param string $urlCleaned
     * @param boolean $activedService
     * @param string $service
     * @param string $projectName
     * @param string $page
     */
    public function addToTestsFile($urlCleaned, $activedService, $service, $projectName, $page)
    {
        if (true === $activedService) {
            $containsFunctionName = 'testIf' . UCFirst($urlCleaned) . 'Contains' . UCFirst($service);
            $checkMethod = UCFirst($projectName) . 'Check' . UCFirst($service);
            $serviceAllTestsByPageContent = $this->_template->render(array(
                    'phpunitTestFunctionName' => $containsFunctionName,
                    'page' => $page,
                    'checkMethod' => $checkMethod
                )
            );
            $this->_testFiles['present'][strtolower($service)] .= $serviceAllTestsByPageContent;
        } else {
            $dontContainsFunctionName = 'testIf' . UCFirst($urlCleaned) . 'DoesntContain' . UCFirst($service);
            $checkNotPresentMethod = UCFirst($projectName) . 'CheckNotPresent' . UCFirst($service);
            $serviceAllTestsByPageContent = $this->_template->render(array(
                    'phpunitTestFunctionName' => $dontContainsFunctionName,
                    'page' => $page,
                    'checkMethod' => $checkNotPresentMethod
                )
            );
            $this->_testFiles['notPresent'][strtolower($service)] .= $serviceAllTestsByPageContent;
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
        $this->_template = $this->_twig->loadTemplate('php/phpunit/phpwpcc_test_content_class.php.tpl');
        foreach ($groupUrl as $portail => $sites) {
            foreach ($sites as $webSite => $pages) {
                foreach ($pages as $page => $pageConfig) {
                    $urlCleaned = wpccUtils::getDomainWithoutExtention($page);
                    foreach ($services as $service) {
                            $activedService = in_array($service, $pageConfig);
                            $this->addToTestsFile($urlCleaned, $activedService, $service, $projectName, $page);
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

        foreach ($this->_testFiles['present'] as $service => $content) {
            $testFileName = UCFirst($projectName) . 'CheckPresent' . UCFirst($service) . '.php';
            wpccFile::writeToFile($this->_testPath . $testFileName , $content . '}');
        }
        foreach ($this->_testFiles['notPresent'] as $service => $content) {
            $testFileName = UCFirst($projectName) . 'CheckNotPresent' . UCFirst($service) . '.php';
            wpccFile::writeToFile($this->_testPath . $testFileName , $content . '}');
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