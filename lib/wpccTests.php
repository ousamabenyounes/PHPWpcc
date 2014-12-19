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
    protected $_projectName;
    protected $_groupUrl;

    protected static $mainClass = 'php/phpunit/phpwpcc_main_class.php.tpl';
    protected static $configTestsClass = 'php/phpunit/phpwpcc_tests_context.php.tpl';
    protected static $presentClass = 'php/phpunit/phpwpcc_test_main_present_class.php.tpl';
    protected static $notPresentClass = 'php/phpunit/phpwpcc_test_main_not_present_class.php.tpl';
    protected static $testContentTpl = 'php/phpunit/phpwpcc_test_content_class.php.tpl';
    protected static $presentServicesLib = 'php/phpunit/lib/phpwpcc_test_main_present_lib.php.tpl';

    /**
     * @param string $root_dir
     * @param string $projectName
     * @param array $groupUrl
     */
    public function __construct($root_dir, $projectName, $groupUrl)
    {
        $this->_rootDir = $root_dir;
        $this->_projectName = $projectName;
        $this->_groupUrl = $groupUrl;
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
     * @param array $services
     */
    public function generateAllTestsCheckMethods($services)
    {
        foreach ($services as $service => $serviceConf) {
            $tplConf = array(
                'projectName' => $this->_projectName,
                'service' => $service,
                'acceptedConfig' => $serviceConf['acceptedConfig']
            );
            $mainPresentClass = wpccTwig::getTemplateContent(self::$presentClass, $tplConf, $this->_rootDir);
            $mainNotPresentClass = wpccTwig::getTemplateContent(self::$notPresentClass, $tplConf, $this->_rootDir);
            foreach ($this->_groupUrl as $portail => $sites) {
                $this->_testFiles['present'][$service][$portail] = $mainPresentClass;
                $this->_testFiles['present'][$service][$portail . 'nbTests'] = 0;
                $this->_testFiles['present'][$service][$portail . 'TestsFileNames'] = array();
                $this->_testFiles['notPresent'][$service][$portail] = $mainNotPresentClass;
                $this->_testFiles['notPresent'][$service][$portail . 'nbTests'] = 0;
                $this->_testFiles['notPresent'][$service][$portail . 'TestsFileNames'] = array();
            }
        }
    }

    /**
     * This function add function to all checkPresent CheckNotPresent files
     *
     * @param boolean $activedService
     * @param string $service
     * @param string $page
     * @param string $portail
     **/
    public function addToTestsFile($activedService, $service, $page, $portail)
    {
        $urlCleaned = wpccUtils::getDomainWithoutExtention($page);
        if (true === $activedService && !in_array($urlCleaned, $this->_urlAdded['present'][$service])) {
            $this->_urlAdded['present'][$service][] = $urlCleaned;
            $containsFunctionName = 'testIf' . UCFirst($urlCleaned) . 'Contains' . UCFirst($service);
            $checkMethod = UCFirst($this->_projectName) . 'Check' . UCFirst($service) . 'Present';
            $tplConf = array(
                'phpunitTestFunctionName' => $containsFunctionName,
                'page' => $page,
                'checkMethod' => $checkMethod
            );
            $testContent = wpccTwig::getTemplateContent(self::$testContentTpl, $tplConf, $this->_rootDir);
            $this->_testFiles['present'][$service][$portail] .= $testContent;
            $this->_testFiles['present'][$service][$portail . 'nbTests']++;
            $this->_testFiles['present'][$service][$portail . 'TestsFileNames'][] = $checkMethod;

        } elseif (false === $activedService && !in_array($urlCleaned, $this->_urlAdded['notPresent'][$service])) {
            $this->_urlAdded['notPresent'][$service][] = $urlCleaned;
            $dontContainsFunctionName = 'testIf' . UCFirst($urlCleaned) . 'DoesNotContain' . UCFirst($service);
            $checkNotPresentMethod = UCFirst($this->_projectName) . 'Check' . UCFirst($service) . 'NotPresent';
            $tplConf = array(
                'phpunitTestFunctionName' => $dontContainsFunctionName,
                'page' => $page,
                'checkMethod' => $checkNotPresentMethod
            );
            $testContent = wpccTwig::getTemplateContent(self::$testContentTpl, $tplConf, $this->_rootDir);
            $this->_testFiles['notPresent'][$service][$portail] .= $testContent;
            $this->_testFiles['notPresent'][$service][$portail . 'nbTests']++;
            $this->_testFiles['present'][$service][$portail . 'TestsFileNames'][] = $checkNotPresentMethod;
        }

    }


    /**
     * This function generate all check methods for all pages
     *
     * @param array $services
     */
    public function generateAllTestsByPagesMethodsContent($services)
    {
        foreach ($this->_groupUrl as $portail => $sites) {
            foreach ($sites as $webSite => $pages) {
                foreach ($pages as $page => $pageConfig) {
                    foreach ($services as $service => $files) {
                        $activedService = in_array(strtolower($service), $pageConfig);
                        $this->addToTestsFile($activedService, $service, $page, $portail);
                    }
                }
            }
        }
        $this->generateAllTestsByPagesMethodsFiles();
    }


    /**
     * This function generate all PhpUnit test files by service
     *
     */
    public function generateAllTestsByPagesMethodsFiles()
    {
        foreach ($this->_testFiles['present'] as $service => $portails) {
            foreach ($portails as $portail => $content) {
                if (!strpos($portail, 'nbTests') && !strpos($portail, 'TestsFileNames')){
                    $testFileName = UCFirst($this->_projectName) .'Check' . UCFirst($service) .
                    'PresentOn' . $portail . '.php';
                    wpccFile::writeToFile($this->_testPath . $testFileName, $content . '}');
                }
            }
        }
        foreach ($this->_testFiles['notPresent'] as $service => $portails) {
            foreach ($portails as $portail => $content) {
                if (!strpos($portail, 'nbTests' && !strpos($portail, 'TestsFileNames'))){
                    $testFileName = UCFirst($this->_projectName) . 'Check' . UCFirst($service) .
                        'NotPresentOn' . $portail .'.php';
                    wpccFile::writeToFile($this->_testPath . $testFileName, $content . '}');
                }
            }
        }
    }


    /**
     * This function generate the main PhpWpcc Test Class
     *
     */
    public function generateMainTestsClass()
    {
        $tplConf = array('projectName' => $this->_projectName);
        $mainTestClassFile = $this->_testPath . UCFirst($this->_projectName) . 'Check.php';
        wpccTwig::saveFileToTpl(self::$mainClass, $tplConf, $mainTestClassFile, $this->_rootDir);
    }

    /**
     * This function generate the main PhpWpcc Test Class
     *
     */
    public function generateConfigTestsFile($type)
    {
        $tplConf = array(
                        'type' => $type,
                        'testFiles' => $this->_testFiles[$type],
                        'projectName' => $this->_projectName
        );
        $configTestsFile = $this->_testPath . UCFirst($this->_projectName) . 'Check' . UCFirst($type) . 'Config.php';
        wpccTwig::saveFileToTpl(self::$configTestsClass, $tplConf, $configTestsFile, $this->_rootDir);
    }

    /**
     * This function generate all Phpunit Tests
     *
     * @param array $services
     */
    public function generateAllTests($services)
    {
        $this->generateAllTestInit($services);
        $this->generateMainTestsClass();
        $this->generateAllTestsCheckMethods($services);
        $this->generateAllTestsByPagesMethodsContent($services);
        $this->generateConfigTestsFile('present');
        $this->generateConfigTestsFile('notPresent');
    }


    public function generateServicesLib($services)
    {
        $serviceCheckLib = $this->_testPath . 'lib/' . UCFirst($this->_projectName) . 'CheckServicesPresent.php';
        $tplConf = array(
            'projectName' => $this->_projectName,
            'services' => $services,
        );
        wpccTwig::saveFileToTpl(self::$presentServicesLib, $tplConf, $serviceCheckLib, $this->_rootDir);
    }


}

?>