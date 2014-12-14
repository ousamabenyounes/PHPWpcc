<?php

class wpccTwig
{

    /**
     * @param string $templateFile
     * @param array $tplConf
     * @param string $filename
     * @param string $root_dir
     */
    public static function saveFileToTpl($templateFile, $tplConf, $filename, $root_dir){
        $tplContent = self::getTemplateContent($templateFile, $tplConf, $root_dir);
        wpccFile::writeToFile($filename, $tplContent);
    }

    /**
     * @param string $templateFile
     * @param array $tplConf
     * @param string $configName
     * @param string $root_dir
     */
    public static function saveConfigToTpl($templateFile, $tplConf, $configName, $root_dir){
        $tplContent = self::getTemplateContent($templateFile, $tplConf, $root_dir);
        wpccConfig::save($root_dir, $tplContent, $configName);
    }

    /**
     * @param string $templateFile
     * @param array $tplConf
     * @param string $root_dir
     * @return string
     */
    public static function getTemplateContent($templateFile, $tplConf, $root_dir)
    {
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem($root_dir . 'views');
        $twig = new Twig_Environment($loader, array('debug' => true));
        $twig->addExtension(new Twig_Extension_Debug());
        $template = $twig->loadTemplate($templateFile);
        $tplContent = $template->render($tplConf);
        return $tplContent;
    }


}
?>