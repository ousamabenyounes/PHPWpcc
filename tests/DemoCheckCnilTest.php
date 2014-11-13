<?php
require("DemoCheck.php");
class DemoCheckCnilTest extends DemoCheck {


   // Check if the given webSiteContent is compatible with allowed Cnil configuration
   // @params String $html Given html content
   // @return Boolean 
   public function DemoCheckCnil($html) {
     // Check fs configuration
     if (FALSE !== strpos($html, "fsd" )) {
          return true;
     }
     return false;
     }

  public function testIfFsdContainsCnil() {        
        $html = $this->getHtmlContent("fsd");
        $this->assertTrue($this->DemoCheckCnil($html), "testIfFsdContainsCnilKO");
    }

}
?>
