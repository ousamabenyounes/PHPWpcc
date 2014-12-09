<?php
$groupUrl3 = array(
{% for portail, sites in groupUrl %}
    "{{ portail }}" => array(
    {% for site in sites %}

        {% for siteUrl, urls in site %}
            "{{ siteUrl }}" => array(
           {% for conf, url in urls %}
              "{{ url }}" => array(),
           {% endfor %}
            ),
        {% endfor %}

{% endfor %}


   ),
{% endfor %}
);
?>