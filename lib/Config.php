<?php

namespace Phpwpcc;

use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

class Config
{
    protected $_servicesConfig;

    /**
     * Config constructor.
     * @param array $servicesConfig
     */
    public function __construct($servicesConfig = array())
    {
        $this->_servicesConfig = $servicesConfig;
    }

    /**
     * This function load the main form to configure your wpcc instance
     * @param array $phpwpcc_config
     */
    public function configureProjectForm($phpwpcc_config)
    {
        echo Twig::getTemplateContent(
            'install/configureProjectForm.twig',
            array (
                'config' => $phpwpcc_config
            )
        );
    }


    /*
     * This function load the main form to configure your wpcc instance
     */
    public function configureProjectGenerate()
    {
        $mailsTo = Utils::getVar('mailsTo', Utils::POST);
        $dumper = new Dumper();
        $yamlContent = $dumper->dump(
            array(
                'projectName' => Utils::getVar('projectName', Utils::POST),
                'mailFrom' =>  Utils::getVar('mailFrom', Utils::POST),
                'smtpHost' =>  Utils::getVar('smtpHost', Utils::POST),
                'smtpPort' =>  Utils::getVar('smtpPort', Utils::POST),
                'smtpLogin' =>  Utils::getVar('smtpLogin', Utils::POST),
                'smtpPassword' =>  Utils::getVar('smtpPassword', Utils::POST),
                'webServiceUrl' => Utils::getVar('webServiceUrl', Utils::POST),
                'mailsTo' => Utils::textareaToArray($mailsTo),
                'cachePurge' => Utils::getVar('cachePurge', Utils::POST),
                'configPurge' => Utils::getVar('configPurge', Utils::POST),
            ), 2
        );
        Config::save($yamlContent, 'wpcc_config');
    }


    /**
     * @param string $content
     * @param string $fileName
     * @param string $root_dir
     */
    public static function save($content, $fileName, $root_dir = '')
    {
        $configDir =  $root_dir . 'config/';
        if (!is_dir($configDir)) {
            mkdir($configDir);
        }
        File::writeToFile($configDir . $fileName .'.yml', $content, false);
	    ConfigLog::save($content, $fileName, $root_dir);
    }

    /**
     * @param string $varName
     * @param string $root_dir
     *
     * @return string | null
     */
    public static function getVarFromConfig($varName, $root_dir = '') {
        $configArray = self::getConfigArray('config', $root_dir);
        if (isset($configArray[$varName])) {
            return $configArray[$varName];
        }
        return null;
    }

    /**
     * @param string $configFileName
     * @param string $arrayName
     * @param string $root_dir
     *
     * @return array | null
     */
    public static function getConfigArray($configFileName, $root_dir = '')
    {
        $yaml = new Parser();
        $filename = $root_dir . 'config/wpcc_' . $configFileName .'.yml';
        $configArray = $yaml->parse(file_get_contents($filename));

        return $configArray;
    }
}
