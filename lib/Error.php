<?php

namespace Phpwpcc;

use Symfony\Component\HttpFoundation\RedirectResponse;

class Error extends Base
{
    protected $exception;

    const ERROR_URL = 'error.php';
    const TPL_ERROR_INDEX = 'error/index.html.twig';

    /**
     * @param \Exception	$exception
     * @param Array	 	$services
     * @param String	 	$rootDir
     */
    public function __construct($exception, $services, $rootDir = '')
    {
	parent::__construct($services, $services);
	$this->setException($exception);
    }

   /**
     * @param  \Exception	$exception
     * @return \Phpwpcc\Error   $error
     */
    public function setException($exception)
    {
        $this->exception = $exception;

        return $this;
    }

    /**
     * @return  String          $exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * This function redirect to the main error page
     */
    public function sendRedirection()
    {
	echo (new RedirectResponse(self::ERROR_URL));	
    } 

    public function printIndex()
    {
	echo Twig::getTemplateContent(
                self::TPL_ERROR_INDEX,
                array (
                    'services' => $this->getServices(),
                )
       ); 
    }
}
