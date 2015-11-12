<?php

namespace Phpwpcc;

use Twig_Autoloader;
use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Extension_Debug;

class Twig
{

    /**
     * @var Singleton
     * @access private
     * @static
     */
    private static $_instance = null;

    private function __construct() {}
    private function __clone() {}


    /**
     * Méthode qui crée l'unique instance de la classe
     * si elle n'existe pas encore puis la retourne.
     *
     * @param string $root_dir
     * @return Twig_environment
     */
    public static function getInstance($root_dir = '') {
    
        if (is_null(self::$_instance)) {
            Twig_Autoloader::register();
            $loader = new Twig_Loader_Filesystem($root_dir . 'views');
            self::$_instance = new Twig_Environment($loader, array('debug' => true));
            self::$_instance->addExtension(new Twig_Extension_Debug());
        }

        return self::$_instance;
    }


    /**
     * @param string $templateFile
     * @param array $tplConf
     * @param string $filename
     * @param string $root_dir
     */
    public static function saveFileToTpl($templateFile, $tplConf, $filename, $root_dir = ''){
        $tplContent = self::getTemplateContent($templateFile, $tplConf, $root_dir);
        File::writeToFile($filename, $tplContent);
    }

    /**
     * @param string $templateFile
     * @param array $tplConf
     * @param string $configName
     * @param string $root_dir
     */
    public static function saveConfigToTpl($templateFile, $tplConf, $configName, $root_dir = ''){
        $tplContent = self::getTemplateContent($templateFile, $tplConf, $root_dir);	
        Config::save($tplContent, $configName, $root_dir);
    }

    /**
     * @param string $templateFile
     * @param array $tplConf
     * @param string $root_dir
     * @return string
     */
    public static function getTemplateContent($templateFile, $tplConf = array(), $root_dir = '')
    {
        $twig = self::getInstance($root_dir);
        $template = $twig->loadTemplate($templateFile);
        $tplContent = $template->render($tplConf);

        return $tplContent;
    }


}
