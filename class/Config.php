<?php

namespace Wpcc;

class Config
{
    protected $_servicesConfig;

    /**
     * @param array $servicesConfig
     */
    public function __construct($servicesConfig = array())
    {
        $this->_servicesConfig = $servicesConfig;
    }

    /*
     * This function load the main form to configure your wpcc instance
     */
    public function configureProjectForm($phpwpcc_config)
    {
        echo Twig::getTemplateContent(
            'install/configureProjectForm.tpl',
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
        $phpTemplate = Twig::getTemplateContent(
            'php/config.php.tpl',
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
            )
        ); 
        Config::save($phpTemplate, 'wpcc_config');
    }

    /**
     *
     * @param string $content
     * @param array $fileName
     * @param array $root_dir
     */
    public static function save($content, $fileName, $root_dir = '')
    {
        $configDir =  $root_dir . 'config/';
        if (!is_dir($configDir)) {
            mkdir($configDir);
        }
        File::writeToFile($configDir . $fileName .'.php', $content, false);
	ConfigLog::save($content, $fileName, $root_dir);
    }

    /**
     * @param string $varName
     * @param string $root_dir
     *
     * @return string | null
     */
    public static function getVarFromConfig($varName, $root_dir = '') {
        require ($root_dir . 'config/wpcc_config.php');
        if (isset($phpwpcc_config[$varName])){

            return $phpwpcc_config[$varName];
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
    public static function getConfigArray($configFileName, $arrayName, $root_dir = '') {
        require ($root_dir . 'config/wpcc_' . $configFileName .'.php');
        if (isset($$arrayName))
	{
	
            return $$arrayName;
        }

        return null;
    }
}
