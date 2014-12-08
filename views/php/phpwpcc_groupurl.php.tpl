<?php
$groupUrl = array(
{% for portail, sites in groupUrl %}
   "{{ portail }}" => array(
    {% for site in sites %}

        {% for siteUrl, urls in site %}
            "{{ siteUrl }}" => array(
           {% for url in urls %}
              "{{ url }}" => array(''),
           {% endfor %}
            ),
        {% endfor %}

{% endfor %}


   ),
{% endfor %}
);
?>