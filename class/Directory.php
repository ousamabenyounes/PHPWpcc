<?php

namespace Wpcc;

class Directory
{

    /**
     * This function create a new directory
     *
     * @param string $newDirectory
     * @param int    $mode
     */
    public static function createDirectory($newDirectory, $mode = 0755)
    {
        try {
            if (!is_dir($newDirectory)) {
                mkdir($newDirectory, $mode);
            }
        } catch (\Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

}
