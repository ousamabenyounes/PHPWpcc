<?php
$groupUrl = array(
{% for portail, sites in groupUrl %}
    "{{ portail }}" => array(
    {% for site in sites %}

        {% for siteUrl, services in site %}
            "{{ siteUrl }}" => array({% for service in services %}"{{ service }}" ,{% endfor %}),
        {% endfor %}

{% endfor %}


   ),
{% endfor %}
);
?>