<?php

namespace Phpwpcc;

class ConfigLog
{

    /**
     *
     * @param string $content
     * @param array $fileName
     * @param array $root_dir
     */
    public static function save($content, $fileName, $root_dir = '')
    {
        $configLogDir = $root_dir . 'config/history/';
        if (!is_dir($configLogDir)) {
            mkdir($configLogDir);
        }
        if (!is_dir($configLogDir . $fileName)) {
            mkdir($configLogDir . $fileName);
        }
        $dateString = date("YmdHis");
        File::writeToFile($configLogDir . $fileName . '/' . $dateString . '.php', $content, false);
        ConfigLog::purge($configLogDir, $fileName, $dateString, $root_dir);
    }

    /**
     * @param string $configLogDir
     * @param string $fileName
     * @param string $dateString
     * @param string $root_dir
     */
    public static function purge($configLogDir, $fileName, $dateString, $root_dir = '') {
        $configPurge = (int) Config::getVarFromConfig('configPurge', $root_dir);
        require ($root_dir . 'config/wpcc_purge.php');
        while ($configPurge <= sizeof($purgeConfig[$fileName])) {
            $configBackupFilename = array_shift($purgeConfig[$fileName]);
            Utils::execCmd('rm ' . $configLogDir . $fileName . '/' . $configBackupFilename . '.php');
        }
        $purgeConfig[$fileName][] = $dateString;
        Twig::saveFileToTpl(
            'purge/update.php.tpl',
            array(
                'purgeConfig' => var_export($purgeConfig, true)
            ),
            $root_dir . 'config/wpcc_purge.php'
        );
    }
}
