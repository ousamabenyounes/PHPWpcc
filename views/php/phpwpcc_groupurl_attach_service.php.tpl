<?php
$groupUrl = array(
{% for portail, sites in groupUrl %}
    "{{ portail }}" => array(
    {% for website, pages in sites %}

        "{{ website }}" => array(
        {% for page, services in pages %}
            "{{ page }}" => array({% for service in services %}"{{ service }}" ,{% endfor %}),
        {% endfor %}
    ),
    {% endfor %}


   ),
{% endfor %}
);
?>