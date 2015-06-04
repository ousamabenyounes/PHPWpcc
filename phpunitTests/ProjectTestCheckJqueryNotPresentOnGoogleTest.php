<?php

namespace Wpcc;

require_once("ProjectTestCheck.php");

class ProjectTestCheckJqueryNotPresentOnGoogleTest extends ProjectTestCheck {

   // Check if the given webSiteContent is not containing Jquery configuration
   // @params String $html Given html content
   // @return Boolean
   public function ProjectTestCheckJqueryNotPresent($html) {
    // Check Jquery configuration
    if (FALSE !== strpos($html, "Jquery")) {
         return false;
    }
    return true;
  }


  /**
   * @group runThisTest
   */
  public function testIfWwwGoogleFrDoesNotContainJquery() {
        try {
            $page = 'http://www.google.fr';
            $fctName = 'testIfWwwGoogleFrDoesNotContainJquery';
            $config = array('Jquery', $page, $fctName, 'Google');
            $html = $this->getHtmlContent($page);
            $this->assertTrue($this->ProjectTestCheckJqueryNotPresent($html), 'Test KO for ' . $page. ' url');
            $this->nextTest($config, "NotPresent");
        } catch (\Exception $e) {
            $this->nextTest($config, "NotPresent", $e->getMessage());
        }
    }
}