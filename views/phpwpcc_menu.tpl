{% block content %}
<div id="sse2">
    <div id="sses2">
        <ul>
            <li><a href="serviceUpdate.php">[ UPDATE SERVICES ] </a></li>


            {% for service, serviceConfig in services %}
                <li><a href="service.php?p={{ service|lower }}">{{ service|upper }}</a></li>
            {% endfor %}

            <li><a href="testsUpdate.php">[ GENERATE ALL TESTS ] </a></li>

        </ul>
    </div>
</div>
{% endblock %}