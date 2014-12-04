{% extends "install/phpwpcc_base.tpl" %}
{% block title %}{{ parent() }} - Service Configuration{% endblock %}
{% block content %}
     {% for key, service in services %}
     {% if loop.index is divisibleby(2)  %}
      <div id="service_{{ service }}" class="phpwpcc_service phpwpcc_service_bis" >
     {% else %}
      <div id="service_{{ service }}" class="phpwpcc_service" >
     {% endif %}
	<h2 class="phpwpcc_h2">{{ service }} Service Configuration</h2>
	<input type="hidden" name="service[]" value="{{ service }}">
	<div id="service_{{ service }}_config" class="phpwpcc_service_cfg">
	  <input name="nbfile_{{ service }}" type="hidden" value="{{ attribute(nbFileConfig, key) }}">
	  {% set nbFiles = attribute(nbFileConfig, key) %}
	  {% for ind in 1..nbFiles  %}
	     <div id="version_{{ service }}_{{ind}}_block" class="phpwpcc_version">
             	  VERSION: <input type="text" name="version_{{ service }}_{{ ind }}" />
             </div>
             <div id="file_{{ service }}" class="phpwpcc_file">
             	  FILES:
            	  <textarea name="file_{{ service }}_{{ ind }}" rows="4" cols="100"></textarea>
             </div>
	  {% endfor %}

        </div>
      </div>
      {% endfor %}    
      <input type="submit" value="Generate Configuration & PHPUnit Tests" class="phpwpcc_generate">
      <input type="hidden" name="nextStep" value="configureServicesFormGenerate" />
{% endblock %}
