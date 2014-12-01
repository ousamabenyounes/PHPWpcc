<?php
$groupUrl = array(
{% for portail, urls in groupUrl %}
   "{{ portail }}" => array(
   {% for url in urls %}
      "{{ url }}",
   {% endfor %}
   ),
{% endfor %}
);
?>