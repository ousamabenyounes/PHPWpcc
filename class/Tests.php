<?php

namespace Wpcc;

class Tests 
{
    protected $_rootDir;
    protected $_testFiles;
    protected $_urlAdded;
    protected $_projectName;
    protected $_groupUrl;
    protected $_services;
    protected $_reporting;

    const TESTS_OK = 'testsOk';
    const TESTS_FAILED = 'testsFailed';
    const NOT_PRESENT = 'NotPresent';
    const PRESENT = 'Present';
    const TEST_PATH_CONFIG = 'config/';
    const TEST_PATH_LIB = 'lib/';
    const TEST_PATH = 'phpunitTests/';
    const TESTS_STATUS_DIR = 'phpunitTests/status/current/';
    const PRESENT_SERVICES_LIB = 'php/phpunit/lib/phpwpcc_test_main_present_lib.php.tpl';
    const TEST_CONTENT_TPL = 'php/phpunit/phpwpcc_test_content_class.php.tpl';
    const NOT_PRESENT_CLASS = 'php/phpunit/phpwpcc_test_main_not_present_class.php.tpl';
    const PRESENT_CLASS = 'php/phpunit/phpwpcc_test_main_present_class.php.tpl';
    const MAIN_CLASS = 'php/phpunit/phpwpcc_main_class.php.tpl';
    const CONFIG_TESTS_CLASS = 'php/phpunit/config/phpwpcc_tests_context.php.tpl';
    const TEST_URL = 0;
    const TEST_STACK = 1;

    /**
     * @param string $root_dir
     * @param string $projectName
     * @param array  $groupUrl
     * @param string $root_dir
     */
    public function __construct($projectName, $groupUrl, $services, $root_dir = '')
    {
        $this->_rootDir = $root_dir;
        $this->_projectName = $projectName;
        $this->_groupUrl = $groupUrl;
        $this->_services = $services;
        $this->_testFiles = array();
    }


    private function generateAllTestInit()
    {
        $this->_urlAdded[self::NOT_PRESENT] = array();
        $this->_urlAdded[self::PRESENT] = array();
        foreach ($this->_services as $service => $conf) {
            $this->_urlAdded[self::NOT_PRESENT][$service] = array();
            $this->_urlAdded[self::PRESENT][$service] = array();
        }
    }

    /**
     * This function generate all check methods for all services
     */
    public function generateAllTestsCheckMethods()
    {
        foreach ($this->_services as $service => $serviceConf) {
            foreach ($this->_groupUrl as $portail => $sites) {
                $tplConf = array(
                    'projectName' => $this->_projectName,
                    'service' => $service,
                    'acceptedConfig' => $serviceConf['acceptedConfig'],
                    'portail' => $portail,
                );
                $mainPresentClass = Twig::getTemplateContent(self::PRESENT_CLASS, $tplConf);
                $mainNotPresentClass = Twig::getTemplateContent(self::NOT_PRESENT_CLASS, $tplConf);		
                $this->_testFiles[self::PRESENT][$service][$portail] = $mainPresentClass;
                $this->_testFiles[self::PRESENT][$service][$portail . 'nbTests'] = 0;
                $this->_testFiles[self::PRESENT][$service][$portail . 'TestsFileNames'] = array();
                $this->_testFiles[self::NOT_PRESENT][$service][$portail] = $mainNotPresentClass;
                $this->_testFiles[self::NOT_PRESENT][$service][$portail . 'nbTests'] = 0;
                $this->_testFiles[self::NOT_PRESENT][$service][$portail . 'TestsFileNames'] = array();
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
        $urlCleaned = Utils::getDomainWithoutExtention($page);
        if (true === $activedService && !in_array($urlCleaned, $this->_urlAdded[self::PRESENT][$service])) {
            $this->_urlAdded[self::PRESENT][$service][] = $urlCleaned;
            $containsFunctionName = 'testIf' . ucfirst($urlCleaned) . 'Contains' . ucfirst($service);
            $checkMethod = ucfirst($this->_projectName) . 'Check' . ucfirst($service) . self::PRESENT;           
            $tplConf = array(
                'phpunitTestFunctionName' => $containsFunctionName,
                'page' => $page,
                'checkMethod' => $checkMethod,
                'service' => $service,
                'portail' => $portail,
                'type' => self::PRESENT,
            );	    
            $testContent = Twig::getTemplateContent(self::TEST_CONTENT_TPL, $tplConf);
	    
            $this->_testFiles[self::PRESENT][$service][$portail] .= $testContent;
            $this->_testFiles[self::PRESENT][$service][$portail . 'nbTests']++;	    
            $this->_testFiles[self::PRESENT][$service][$portail . 'TestsFileNames'][$containsFunctionName] = $page;
	    
        } elseif (false === $activedService && !in_array($urlCleaned, $this->_urlAdded[self::NOT_PRESENT][$service])) {
            $this->_urlAdded[self::NOT_PRESENT][$service][] = $urlCleaned;
            $dontContainsFunctionName = 'testIf' . ucfirst($urlCleaned) . 'DoesNotContain' . ucfirst($service);
            $checkNotPresentMethod = ucfirst($this->_projectName) . 'Check' . ucfirst($service) . self::NOT_PRESENT;
            $tplConf = array(
                'phpunitTestFunctionName' => $dontContainsFunctionName,
                'page' => $page,
                'checkMethod' => $checkNotPresentMethod,
                'service' => $service,
                'portail' => $portail,
                'type' => self::NOT_PRESENT,
            );
            $testContent = Twig::getTemplateContent(self::TEST_CONTENT_TPL, $tplConf);
            $this->_testFiles[self::NOT_PRESENT][$service][$portail] .= $testContent;
            $this->_testFiles[self::NOT_PRESENT][$service][$portail . 'nbTests']++;
            $this->_testFiles[self::NOT_PRESENT][$service][$portail .
                'TestsFileNames'][$dontContainsFunctionName] = $page;
        }
    }


    /**
     * This function generate all check methods for all pages
     *
     */
    public function generateAllTestsByPagesMethodsContent()
    {
        foreach ($this->_groupUrl as $portail => $sites) {
            foreach ($sites as $webSite => $pages) {
                foreach ($pages as $page => $pageConfig) {
                    foreach ($this->_services as $service => $files) {
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
        foreach ($this->_testFiles[self::PRESENT] as $service => $portails) {
            foreach ($portails as $portail => $content) {
                if (false === strpos($portail, 'nbTests') && false === strpos($portail, 'TestsFileNames')){
                    $testFileName = ucfirst($this->_projectName) .'Check' . ucfirst($service) .                    
		    self::PRESENT . 'On' . ucfirst($portail) . 'Test.php';
                    if (0 !== $this->_testFiles[self::PRESENT][$service][$portail . 'nbTests'])
                    {
                        File::writeToFile($this->_rootDir . self::TEST_PATH . $testFileName, $content . '}');
                    }
                }
            }
        }
        foreach ($this->_testFiles[self::NOT_PRESENT] as $service => $portails) {
            foreach ($portails as $portail => $content) {
                if (false === strpos($portail, 'nbTests') && false === strpos($portail, 'TestsFileNames')){
                    $testFileName = ucfirst($this->_projectName) . 'Check' . ucfirst($service) .
                        self::NOT_PRESENT . 'On' . ucfirst($portail) .'Test.php';
                    if (0 !== $this->_testFiles[self::NOT_PRESENT][$service][$portail . 'nbTests'])
                    {
                        File::writeToFile($this->_rootDir . self::TEST_PATH . $testFileName, $content . '}');
                    }
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
        $mainTestClassFile = $this->_rootDir . self::TEST_PATH . ucfirst($this->_projectName) . 'Check.php';
        Twig::saveFileToTpl(self::MAIN_CLASS, $tplConf, $mainTestClassFile);
    }

    /**
     * This function generate the main PhpWpcc Test Class
     *
     */
    public function generateConfigTestsFile($type)
    {
        foreach ($this->_testFiles[$type] as $service => $portails){
            $tplConf = array(
                'portails' => $portails,
                'service' => $service,
                'type' => ucfirst($type),
                'projectName' => $this->_projectName
            );
            $configTestsFile = $this->_rootDir . self::TEST_PATH . self::TEST_PATH_CONFIG . ucfirst($this->_projectName) .
                'Check' . ucfirst($service) . ucfirst($type) . 'Config.php';

            Twig::saveFileToTpl(self::CONFIG_TESTS_CLASS, $tplConf, $configTestsFile);
        }
    }




    /**
     * This function generate all Phpunit Tests
     *
     */
    public function generateAllTests()
    {
        $this->generateAllTestInit();
        $this->generateMainTestsClass();
        $this->generateAllTestsCheckMethods();
        $this->generateAllTestsByPagesMethodsContent();
        $this->generateConfigTestsFile(self::PRESENT);
        $this->generateConfigTestsFile(self::NOT_PRESENT);
        $this->generateServicesLib();
    }

    /**
     * This function remove all old generated file for the oldProjectName context
     * Tests Files - Status Files - Libraries & Config Files
     *
     * @param string $oldProjectName
     */
    public function purgeOldTest($oldProjectName)
    {

	array_map('unlink', glob($this->_rootDir . self::TESTS_STATUS_DIR . '*'));	

/*        Utils::execCmd('rm ' . $this->_rootDir . self::TESTS_STATUS_DIR . '*');

        Utils::execCmd('rm ' . $this->_rootDir . self::TEST_PATH . self::TEST_PATH_CONFIG . $oldProjectName . '*');
        Utils::execCmd('rm ' . $this->_rootDir . self::TEST_PATH . self::TEST_PATH_LIB . $oldProjectName . '*');
        Utils::execCmd('rm ' . $this->_rootDir . self::TEST_PATH . $oldProjectName . '*');*/
    }


    public function generateServicesLib()
    {
        $serviceCheckLib = $this->_rootDir . self::TEST_PATH . self::TEST_PATH_LIB . ucfirst($this->_projectName) . 'CheckServicesPresent.php';
        $tplConf = array(
            'projectName' => $this->_projectName,
            'services' => $this->_services,
        );
        Twig::saveFileToTpl(self::PRESENT_SERVICES_LIB, $tplConf, $serviceCheckLib);
    }


    public function printIndex()
    {
        $service = Utils::getVar('service');
        if (null !== $service && strlen($service)) {

	    require($this->_rootDir . self::TEST_PATH . self::TEST_PATH_CONFIG . $this->_projectName . 'Check' .
                ucfirst($service) . self::PRESENT . 'Config.php');
	    require($this->_rootDir . self::TEST_PATH  . self::TEST_PATH_CONFIG . $this->_projectName . 'Check' .
                UCFIRST($service) . self::NOT_PRESENT . 'Config.php');
            echo Twig::getTemplateContent(
                'tests/index.tpl',
                array (
                    'services' => $this->_services,
                    'servicePresent' => ${ucfirst($service) . self::PRESENT},
                    'serviceNotPresent' => ${ucfirst($service) . self::NOT_PRESENT},
                    'service' => $service,
                    'firstPortail' => key($this->_groupUrl),
                    'noPreview' => Cache::$noPreviewAvailable
                )
            );
        } else {
            echo Twig::getTemplateContent(
                'tests/results.tpl',
                array (
                    'services' => $this->_services,
                )
            );
        }
    }

    public static function launchPortailTest($projectName, $root_dir = '') {    
        try {
            $service = Utils::getVar('service', Utils::POST);
            $portail = Utils::getVar('portail', Utils::POST);
            $testType = Utils::getVar('testType', Utils::POST);
            $fileName = $projectName . 'Check' . ucfirst($service) . $testType .
                'On' . ucfirst($portail) . 'Test.php';
            Utils::execCmd('cd ' . $root_dir . self::TEST_PATH . ' && phpunit ' . $fileName, $output, $return);
            return json_encode(array('success' => true, 'content' => $portail .' Tests launched successfully'));
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'content' => $e->getMessage()));
        }
    }


    public static function getServicePortailStatus() {
        try {
            $service = Utils::getVar('service', Utils::POST);
            $portail = Utils::getVar('portail', Utils::POST);
            $testType = Utils::getVar('testType', Utils::POST);
            if (null !== $portail && null !== $service && null !== $testType) {
                $fileName = '../' . self::TESTS_STATUS_DIR . ucfirst($service) .
                    ucfirst($portail) . $testType . '.php';
                if (file_exists($fileName) && is_readable($fileName))   {
                    require ($fileName);
                    return json_encode(
                        array(
                            'success' => true,
                            'content' => array(
                                self::TESTS_OK => $testsOk,
                                self::TESTS_FAILED => $testsFailed
                            )
                        )
                    );
                }
                return json_encode(array('success' => false, 'content' => 'File ' .
                        $fileName . ' not found or not readable'));
            }
            return json_encode(array('success' => false, 'content' => 'no data from POST'));
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'content' => $e->getMessage()));
        }
    }

    
    /*
     * @param String $fileName
     */
    public function checkFile($fileName, $root_dir = '')
    {
	$fileName = $root_dir . self::TESTS_STATUS_DIR . $fileName . '.php';
        if (is_file($fileName))
	{
           require ($fileName);
	   $this->_reporting[self::TESTS_OK] = array_merge($this->_reporting[self::TESTS_OK], $testsOk);
	   $this->_reporting[self::TESTS_FAILED] = array_merge($this->_reporting[self::TESTS_FAILED], $testsFailed);	   
        } 
    }


    /**
     * @return integer $nbFailedTest 
     */
    public function getNbTestsFailed()
    {
	return sizeof($this->_reporting[self::TESTS_FAILED]);
    }

    /**
     * @return string $mailReporting
     */
    public function getReporting($root_dir = '')
    {
	 $this->_reporting = array();
	 $this->_reporting[self::TESTS_OK] = array();
	 $this->_reporting[self::TESTS_FAILED] = array();
	 foreach ($this->_services as $service => $config ) {
            foreach ($this->_groupUrl as $portailName => $config) {
                     $servicePortail = UcFirst($service) . UcFirst($portailName);
		     $this->checkFile($servicePortail . self::PRESENT, $root_dir);
		     $this->checkFile($servicePortail . self::NOT_PRESENT, $root_dir);
            }
    	 }

         $nbTestsTotal = sizeof($this->_reporting[self::TESTS_OK]) + sizeof($this->_reporting[self::TESTS_FAILED]);
         $mailReporting = Twig::getTemplateContent(
            'mail/rapport.tpl',
            array('nbTestsTotal' => $nbTestsTotal,
                  'nbTestsFailed' => sizeof($this->_reporting[self::TESTS_FAILED]),
                  'nbTestsOk' =>  sizeof($this->_reporting[self::TESTS_OK]),
                  'testsFailed' => $this->_reporting[self::TESTS_FAILED],
                 ),
	    $root_dir
	);
        return $mailReporting;
    }


    /**
     *
     * @param string $oldProjectName
     */
    public function regenerateTests($oldProjectName = null)
    {
    	if (null !== $oldProjectName) {
           $this->purgeOldTest($oldProjectName);
    	}
    	$this->generateAllTests();
    }

}

?>

