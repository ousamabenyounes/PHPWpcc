<?php
$servicesConfig = array(
{% for key, service in post['service'] %}
  "{{ service }}" => array(
       'acceptedConfig' => array( {% set nbFiles = post['nbfile_'~service] %}
   {% for ind in 1..nbFiles  %}
      {% set filesToCheckArray = post['file_'~service~'_'~ind]|split("\r") %}      
      	 "{{ post['version_'~service~'_'~ind] }}" => array(
	    {% for keyFiles, file in filesToCheckArray  %}
	       "{{ file|trim()|nl2br()|split("\n")|join() }}", 
	    {% endfor %}	   
         ),
   {% endfor %}
   ),
),
{% endfor %}

);
?>