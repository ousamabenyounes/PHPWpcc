<?php

namespace Phpwpcc;

use Symfony\Component\Yaml\Parser;

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
    const TEST_PATH_CONFIG_LIB = 'lib/Tests/';    
    const TEST_PATH = 'phpunitTests/';
    const TESTS_STATUS_DIR = 'phpunitTests/status/current/';

    // Twig Templates filenames
    const PRESENT_SERVICES_LIB = 'php/phpunit/lib/phpwpcc_test_main_present_lib.php.twig';
    const TEST_CONTENT_TPL = 'php/phpunit/phpwpcc_test_content_class.php.twig';
    const NOT_PRESENT_CLASS = 'php/phpunit/phpwpcc_test_main_not_present_class.php.twig';
    const PRESENT_CLASS = 'php/phpunit/phpwpcc_test_main_present_class.php.twig';
    const MAIN_CLASS = 'php/phpunit/phpwpcc_main_class.php.twig';
    const TPL_TESTS_CONFIG_CLASS = 'php/phpunit/lib/phpwpcc_tests_context.php.twig';
    const TPL_TESTS_INDEX = 'tests/index.twig';

    const TEST_URL = 0;
    const TEST_STACK = 1;    
    const TESTS_NAMESPACE = 'Phpwpcc\\Tests\\';

    /**
     * @param string $root_dir
     * @param string $projectName
     * @param array  $groupUrl
     * @param string $root_dir
     */
    public function __construct($projectName, $groupUrl, $services, $root_dir = '')
    {
        $this->_rootDir = $root_dir;
        $this->_groupUrl = $groupUrl;
        $this->_services = $services;
        $this->_testFiles = array();
	$this->setProjectName($projectName);
    }


    private function generateAllTestsInit()
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
                    'projectName' => $this->getProjectName(),
		    'lProjectName' => LCFirst($this->getProjectName()),
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
        Twig::saveFileToTpl(self::MAIN_CLASS, $tplConf, $mainTestClassFile, $this->_rootDir);
    }

    /**
     * This function generate the main PhpWpcc Test Class
     *
     */
    public function generateConfigTestsFile()
    {
	$serviceConf = array();
	foreach ($this->_testFiles as $type => $testFile) {
	  foreach ($testFile as $service => $portails) {
	    $serviceConf[$service][$type] = $portails;
	  }
	}
	foreach ($serviceConf as $service => $mainConfig) {
	  $tplConf = array(
			'projectName' => $this->getProjectName(),
			'service' => $service
		     );
	  foreach ($mainConfig as $type => $config) {
	  	$tplConf['portails' . $type] = $config;
	  }
	  $configTestsClassFile = $this->_rootDir . self::TEST_PATH_CONFIG_LIB . ucfirst($service) . '.php';
	  Twig::saveFileToTpl(self::TPL_TESTS_CONFIG_CLASS, $tplConf, $configTestsClassFile);
	}
    }

    /**
     * This function generate all Phpunit Tests
     */
    public function generateAllTests()
    {
        $this->generateAllTestsInit();
        $this->generateMainTestsClass();
        $this->generateAllTestsCheckMethods();
        $this->generateAllTestsByPagesMethodsContent();
        $this->generateConfigTestsFile();
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
        Utils::execCmd('yes | rm ' . $this->_rootDir . self::TESTS_STATUS_DIR . '*');
        Utils::execCmd('yes | rm ' . $this->_rootDir . self::TEST_PATH . self::TEST_PATH_CONFIG_LIB . ucFirst($oldProjectName) . '*');
        Utils::execCmd('yes | rm ' . $this->_rootDir . self::TEST_PATH_CONFIG_LIB  . '*');
        Utils::execCmd('yes | rm ' . $this->_rootDir . self::TEST_PATH . ucFirst($oldProjectName) . '*');
    }


    public function generateServicesLib()
    {
	$serviceCheckLib = $this->_rootDir . self::TEST_PATH_CONFIG_LIB .  ucfirst($this->_projectName) . 'CheckServicesPresent.php';
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
	   $className = self::TESTS_NAMESPACE. ucfirst($service);	   
           echo Twig::getTemplateContent(
                self::TPL_TESTS_INDEX,
                array (
                    'services' => $this->_services,
                    'servicesPresent' => $className::getServicesPresentConfig(),
                    'servicesNotPresent' => $className::getServicesNotPresentConfig(),
                    'service' => $service,
                    'firstPortail' => key($this->_groupUrl),
                    'noPreview' => Cache::$noPreviewAvailable
                )
            );
        } else {
            echo Twig::getTemplateContent(
                'tests/results.twig',
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
        } catch (\Exception $e) {

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
                    return json_encode(
                        array(
                            'success' => true,
                            'content' => array(
                                self::TESTS_OK => self::getTestArray('testsOk'),
                                self::TESTS_FAILED => self::getTestArray('testsFailed')
                            )
                        )
                    );
                }

                return json_encode(array('success' => false, 'content' => 'File ' .
                        $fileName . ' not found or not readable'));
            }

            return json_encode(array('success' => false, 'content' => 'no data from POST'));
        } catch (\Exception $e) {

            return json_encode(array('success' => false, 'content' => $e->getMessage()));
        }
    }

    
    /*
     * @param String $fileName
     */
    public function checkFile($root_dir = '')
    {
            $testsOk = self::getTestArray('testsOk', $root_dir);
            $testsFailed = self::getTestArray('testsFailed', $root_dir);
            $this->_reporting[self::TESTS_OK] = array_merge($this->_reporting[self::TESTS_OK], $testsOk);
            $this->_reporting[self::TESTS_FAILED] = array_merge($this->_reporting[self::TESTS_FAILED], $testsFailed);
    }

    /**
     * @param $testType
     * @param string $root_dir
     * @return array | null
     */
    public static function getTestArray($testType, $root_dir = '')
    {
        $yaml = new Parser();
        $filename = $root_dir . self::TESTS_STATUS_DIR  . 'test.yml';
        $configArray = $yaml->parse(file_get_contents($filename));
        if (isset($configArray[$testType])) {
            return $configArray[$testType];
        };

        return null;
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
            'mail/rapport.twig',
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
     * @param string $projectName
     */
    private function setProjectName($projectName)
    {
	$this->_projectName = $projectName;
    }

    /**
     * @return string $projectName
     */
    private function getProjectName()
    {
	return $this->_projectName;
    }


    /**
     * @param string $projectName
     * @param string $oldProjectName
     */
    public function regenerateTests($projectName, $oldProjectName = null)
    {
    	if (null === $oldProjectName) {
	   $oldProjectName = Config::getVarFromConfig('projectName', $this->_rootDir);
    	}
	$this->purgeOldTest($oldProjectName);
	$this->setProjectName($projectName);
    	$this->generateAllTests();
    }

}
