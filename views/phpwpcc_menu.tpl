{% block content %}
<div id="sse2">
    <div id="sses2">
        <ul>
            {% for service, serviceConfig in servicesConfig %}
                <li><a href="?p={{ service|lower }}">{{ service|upper }}</a></li>
            {% endfor %}
        </ul>
    </div>
</div>
<script src="js/jquery-ui.custom.min.js" type="text/javascript"></script>
{% endblock %}