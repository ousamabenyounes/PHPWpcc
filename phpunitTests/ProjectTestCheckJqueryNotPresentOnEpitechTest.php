<?php

namespace Wpcc;

require_once("ProjectTestCheck.php");

class ProjectTestCheckJqueryNotPresentOnEpitechTest extends ProjectTestCheck {

   // Check if the given webSiteContent is not containing Jquery configuration
   // @params String $html Given html content
   // @return Boolean
   public function projectTestCheckJqueryNotPresent($html) {
    // Check Jquery configuration
    if (FALSE !== strpos($html, "Jquery")) {
         return false;
    }
    return true;
  }


  /**
   * @group runThisTest
   */
  public function testIfWwwEpitechFrDoesNotContainJquery() {
        try {
            $page = 'http://www.epitech.fr';
            $fctName = 'testIfWwwEpitechFrDoesNotContainJquery';
            $config = array('Jquery', $page, $fctName, 'Epitech');
            $html = $this->getHtmlContent($page);
            $this->assertTrue($this->ProjectTestCheckJqueryNotPresent($html), 'Test KO for ' . $page. ' url');
            $this->nextTest($config, "NotPresent");
        } catch (\Exception $e) {
            $this->nextTest($config, "NotPresent", $e->getMessage());
        }
    }
}