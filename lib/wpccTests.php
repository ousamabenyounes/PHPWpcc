<?php
require('wpcc.php');
require('wpccConfig.php');
require('wpccConfigLog.php');
require('wpccUtils.php');
require('wpccTwig.php');

class wpccTests extends wpcc
{
    protected $_rootDir;
    protected $_testPath;
    protected $_testFiles;
    protected $_urlAdded;

    protected static $mainClass = 'php/phpunit/phpwpcc_main_class.php.tpl';
    protected static $presentClass = 'php/phpunit/phpwpcc_test_main_present_class.php.tpl';
    protected static $notPresentClass = 'php/phpunit/phpwpcc_test_main_not_present_class.php.tpl';
    protected static $testContent = 'php/phpunit/phpwpcc_test_content_class.php.tpl';
    protected static $presentServicesLib = 'php/phpunit/lib/phpwpcc_test_main_present_lib.php.tpl';

    /**
     * @param array $servicesConfig
     * @param int $servicesNbFilesConfig
     */
    public function __construct($root_dir)
    {
        $this->_rootDir = $root_dir;
        $this->_testFiles = array();
        $this->_testPath = $this->_rootDir . 'phpunitTests/';
    }


    /**
     * @param array $servicesConfig
     */
    private function generateAllTestInit($servicesConfig)
    {
        $this->_urlAdded['notPresent'] = array();
        $this->_urlAdded['present'] = array();
        foreach ($servicesConfig as $service => $conf) {
            $this->_urlAdded['notPresent'][$service] = array();
            $this->_urlAdded['present'][$service] = array();
        }
    }

    /**
     * This function generate all check methods for all services
     *
     * @param string $projectName
     * @param array $services
     */
    public function generateAllTestsCheckMethods($projectName, $services)
    {
        foreach ($services as $service => $serviceConf) {
            $tplConf = array(
                'projectName' => $projectName,
                'service' => $service,
                'acceptedConfig' => $serviceConf['acceptedConfig']
            );
            $serviceMainClass = wpccTwig::getTemplateContent(self::$presentClass, $tplConf, $this->_rootDir);
            $this->_testFiles['present'][$service] = $serviceMainClass;
            $serviceMainClass = wpccTwig::getTemplateContent(self::$notPresentClass, $tplConf, $this->_rootDir);
            $this->_testFiles['notPresent'][$service] = $serviceMainClass;
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
     **/
    public function addToTestsFile($urlCleaned, $activedService, $service, $projectName, $page)
    {
        if (true === $activedService && !in_array($urlCleaned, $this->_urlAdded['present'][$service])) {
            $this->_urlAdded['present'][$service][] = $urlCleaned;
            $containsFunctionName = 'testIf' . UCFirst($urlCleaned) . 'Contains' . UCFirst($service);
            $checkMethod = UCFirst($projectName) . 'Check' . UCFirst($service) . 'Present';
            $tplConf = array(
                'phpunitTestFunctionName' => $containsFunctionName,
                'page' => $page,
                'checkMethod' => $checkMethod
            );
            $testContent = wpccTwig::getTemplateContent(self::$testContent, $tplConf, $this->_rootDir);
            $this->_testFiles['present'][$service] .= $testContent;

        } elseif (false === $activedService && !in_array($urlCleaned, $this->_urlAdded['notPresent'][$service])) {
            $this->_urlAdded['notPresent'][$service][] = $urlCleaned;
            $dontContainsFunctionName = 'testIf' . UCFirst($urlCleaned) . 'DoesntContain' . UCFirst($service);
            $checkNotPresentMethod = UCFirst($projectName) . 'Check' . UCFirst($service) . 'NotPresent';
            $tplConf = array(
                'phpunitTestFunctionName' => $dontContainsFunctionName,
                'page' => $page,
                'checkMethod' => $checkNotPresentMethod
            );
            $testContent = wpccTwig::getTemplateContent(self::$testContent, $tplConf, $this->_rootDir);
            $this->_testFiles['notPresent'][$service] .= $testContent;
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
                    $urlCleaned = wpccUtils::getDomainWithoutExtention($page);
                    foreach ($services as $service => $files) {
                        $activedService = in_array(strtolower($service), $pageConfig);
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
    public function generateAllTestsByPagesMethodsFiles($projectName)
    {
        foreach ($this->_testFiles['present'] as $service => $content) {
            $testFileName = UCFirst($projectName) . 'Check' . UCFirst($service) . 'Present.php';
            wpccFile::writeToFile($this->_testPath . $testFileName, $content . '}');
        }
        foreach ($this->_testFiles['notPresent'] as $service => $content) {
            $testFileName = UCFirst($projectName) . 'Check' . UCFirst($service) . 'NotPresent' . '.php';
            wpccFile::writeToFile($this->_testPath . $testFileName, $content . '}');
        }
    }


    /**
     * This function generate the main PhpWpcc Test Class
     *
     * @param string $projectName
     */
    public function generateMainTestsClass($projectName)
    {
        $tplConf = array('projectName' => $projectName);
        $mainTestClassFile = $this->_testPath . UCFirst($projectName) . 'Check.php';
        wpccTwig::saveFileToTpl(self::$mainClass, $tplConf, $mainTestClassFile, $this->_rootDir);
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
        $this->generateAllTestInit($services);
        $this->generateMainTestsClass($projectName);
        $this->generateAllTestsCheckMethods($projectName, $services);
        $this->generateAllTestsByPagesMethodsContent($projectName, $services, $groupUrl);
    }


    public function generateServicesLib($projectName, $services)
    {
        $serviceCheckLib = $this->_testPath . 'lib/' . UCFirst($projectName) . 'CheckServices.php';
        $tplConf = array(
            'projectName' => $projectName,
            'service' => $service,
            'acceptedConfig' => $serviceConf['acceptedConfig']
        );

        foreach ($services as $service => $files) {
            wpccTwig::getTemplateContent(self::$presentClass, $tplConf, $this->_rootDir);
            die($serviceCheckLib);
            wpccTwig::saveFileToTpl(self::$mainClass, $tplConf, $mainTestClassFile, $this->_rootDir);
        }
    }


}

?>