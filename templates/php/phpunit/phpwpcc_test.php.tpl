<?
require("{{ projectName }}Check.php");
class {{ projectName }}Check{{ service }}Test extends {{ projectName }}Check {


   // Check if the given webSiteContent is compatible with allowed {{ service }} configuration
   // @params String $html Given html content
   // @return Boolean
   public function {{ projectName }}Check{{ service }}($html) {
     
      {% for version, files in acceptedConfig %}
      // Check {{ version }} configuration
      	 {% for key, file in files %}if (FALSE !== strpos($html, "{{ file }}" ){% endfor %}

     	 if (FALSE !== strpos($html, "file1" ) && FALSE !== strpos($html, "file2" )) {
             return true;
      	 }
      {% endfor %}

     // Check {{ version }} configuration
     if (FALSE !== strpos($html, "file1" ) && FALSE !== strpos($html, "file2" )) {
          return true;
     }

     // Check serviceconf2 configuration
     if (FALSE !== strpos($html, "file3" ) && FALSE !== strpos($html, "
file4" )) {
          return true;
     }



     return false;
     }

  public function testIfSite1fr^MContainsPub2() {
        $html = $this->getHtmlContent("www.site1.fr^M");
        $this->assertTrue($this->DemoCheckpub2($html), "testIfSite1fr^MContainsPub2KO");
    }

  public function testIfSite2frContainsPub2() {
        $html = $this->getHtmlContent("www.site2.fr");
        $this->assertTrue($this->DemoCheckpub2($html), "testIfSite2frContainsPub2KO");
    }

}
?>
