<?php

${{ service }}{{ type }} = array(
            {% for portail, content in portails %}{% if 'nbTests' in portail or 'TestsFileNames' in portail %}{% else %}
{% set nbTestsIndex = portail ~ 'nbTests' %}
{% set testMethodNamesIndex = portail ~ 'TestsFileNames' %}
'{{ portail }}' => array(
                                'nbTests' => {{attribute(portails, nbTestsIndex)}},
                                'fileName' => "{{ projectName|capitalize ~ 'Check' ~  service|capitalize ~  testType|capitalize ~ 'On' ~ portail|capitalize }} ",
                                'testMethodNames' => array(
                                {% for key, testMethodName in  attribute(portails, testMethodNamesIndex) %}
                                    "{{ key }}" => "{{ testMethodName }}",
                                {% endfor %}
                                ),
            ),
       {% endif %}
    {% endfor %}


   );
