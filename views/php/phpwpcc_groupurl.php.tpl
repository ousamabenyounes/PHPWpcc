<?php
$groupUrl = array(
{% for portail, sites in groupUrl %}
   "{{ portail }}" => array(
    {% for site in sites %}

        {% for website, pages in site %}
            "{{ website }}" => array(
           {% for page in pages %}
              "{{ page }}" => array(),
           {% endfor %}
            ),
        {% endfor %}

{% endfor %}


   ),
{% endfor %}
);
?>