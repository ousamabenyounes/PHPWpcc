<?php

namespace Phpwpcc\Tests;

class  {{ service|capitalize }} {
      
      /**
       * @return array
       */
      public static function getServicesNotPresentConfig()
      {
	return   array(
            {% for portail, content in portailsNotPresent %}{% if 'nbTests' in portail or 'TestsFileNames' in portail %}{% else %}
{% set nbTestsIndex = portail ~ 'nbTests' %}
{% set testMethodNamesIndex = portail ~ 'TestsFileNames' %}


'{{ portail }}' => array(
                                'nbTests' => {{attribute(portailsNotPresent, nbTestsIndex)}},
                                'fileName' => "{{ projectName|capitalize ~ 'Check' ~  service|capitalize ~  testType|capitalize ~ 'On' ~ portail|capitalize }} ",
                                'testMethodNames' => array(
                                {% for key, testMethodName in  attribute(portailsNotPresent, testMethodNamesIndex) %}
                                    "{{ key }}" => "{{ testMethodName }}",
                                {% endfor %}
                                ),
            ),
       {% endif %}
    {% endfor %}
       	 );
       }


      /**
       * @return array
       */
      public static function getServicesPresentConfig()
      {
	return   array(
            {% for portail, content in portailsPresent %}{% if 'nbTests' in portail or 'TestsFileNames' in portail %}{% else %}
{% set nbTestsIndex = portail ~ 'nbTests' %}
{% set testMethodNamesIndex = portail ~ 'TestsFileNames' %}
'{{ portail }}' => array(
				'nbTests' => {{attribute(portailsPresent, nbTestsIndex)}},
                                'fileName' => "{{ projectName|capitalize ~ 'Check' ~  service|capitalize ~  testType|capitalize ~ 'On' ~ portail|capitalize }} ",
				'testMethodNames' => array(
							{% for key, testMethodName in  attribute(portailsPresent, testMethodNamesIndex) %}
                                    "{{ key }}" => "{{ testMethodName }}",
				    	    	{% endfor %}
								),
            ),
       {% endif %}
    {% endfor %}
         );
       }

}
