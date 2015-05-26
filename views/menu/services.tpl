{% block content %}
<div id="sse2">
    <div id="sses2">
        <ul>
            <li><a href="install.php">[ CONFIG PHPWPCC ] </a></li>
            <li><a href="groupUrlUpdate.php">[ PORTAILS ] </a></li>
            <li><a href="serviceUpdate.php">[ SERVICES ] </a></li>
            {% for service, serviceConfig in services %}<li><a href="service.php?p={{ service|lower }}">{% if loop.first %} [ {% endif %}{{ service }}{% if loop.last %} ] {% endif %}</a></li>
            {% endfor %}<li><a href="tests.php">[ TESTS RESULTS ] </a></li>
        </ul>
    </div>
</div>
{% endblock %}