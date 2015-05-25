<?
namespace Wpcc;

require_once("{{ projectName }}Check.php");
class {{ projectName }}Check{{ service }}PresentOn{{ portail }}Test extends {{ projectName }}Check {

   // Check if the given webSiteContent is compatible with allowed {{ service }} configuration
   // @params String $html Given html content
   // @return Boolean
   public function {{ projectName }}Check{{ service }}Present($html) {
{% for version, files in acceptedConfig %}
    // Check {{ version }} configuration
    if ({% for key, file in files %} FALSE !== strpos($html, "{{ file }}" ){% if loop.last %}) {
    {% else %} &&
{% endif %} {% endfor %}
    return true;
    }
{% endfor %}
    return false;
  }


