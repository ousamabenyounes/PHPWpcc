<?php

namespace Phpwpcc;

Abstract class Base
{
    protected $rootDir;
    protected $services;

    /**
     * @param	Array		$services
     * @param	String		$rootDir
     */
    public function __construct($services, $rootDir = '')
    {
	$this->setServices($services);
	$this->setRootDir($rootDir);
    }

    /**
     * @param   Array           $services
     * @return  \Phpwpcc\Base   $baseObj
     */
    public function setServices($services)
    {
	$this->services = $services;

        return $this;
    }

    /**
     * @return  Array          $services
     */
    public function getServices()
    {
        return $this->sercices;
    }

    /**
     * @param  String		$rootDir
     * @return \Phpwpcc\Base	$baseObj
     */
    public function setRootDir($rootDir)
    {
	$this->rootDir = $rootDir;

	return $this;
    }

    /**
     * @return	String		$rootDir
     */
    public function getRootDir()
    {
	return $this->rootDir;
    }

}
