<?php

namespace Phpwpcc\Tests;

class {{ projectName }}CheckServicesPresent {

{% for service, config in services %}

   // Check if the given webSiteContent is compatible with allowed {{ service }} configuration
   // @params String $html Given html content
   // @return Boolean
   public static function {{ service }}($html) {
{% for acceptedConfig, acceptedConfigfiles in config %}
 {% for files in acceptedConfigfiles %}

    // Check {{ version }} configuration
    if ({% for key, file in files %}FALSE !== strpos($html, "{{ file }}"){% if loop.last %}) {
    {% else %} &&
    {% endif %} {% endfor %}
    return true;
    }
 {% endfor %}
{% endfor %}
    return false;
  }

{% endfor %}
}
