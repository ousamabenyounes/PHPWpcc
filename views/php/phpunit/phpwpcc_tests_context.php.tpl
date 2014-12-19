<?php
$tests{{ testType }}Context = array(
{% for service, portails in testFiles %}
"{{ service }}" => array(
            {% for portail, content in portails %}{% if 'nbTests' in portail or 'TestsFileNames' in portail %}{% else %}
{% set nbTestsIndex = portail ~ 'nbTests' %}
{% set testMethodNames = portail ~ 'TestsFileNames' %}
'{{ portail }}' => array(
                                'nbTests' => {{attribute(portails, nbTestsIndex)}},
                                'fileName' => "{{ projectName|capitalize ~ 'Check' ~  service|capitalize ~  testType|capitalize ~ 'On' ~ portail|capitalize }} ",
                                'testMethodNames' => array(
                                {% for testMethodName in testMethodNames%}
                                    "{{ testMethodName }}",
                                {% endfor %}
                                ),
            ),
       {% endif %}
    {% endfor %}


   ),
{% endfor %}
);
?>
