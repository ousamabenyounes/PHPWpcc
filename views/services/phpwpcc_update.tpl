{% extends "install/phpwpcc_base.tpl" %}
{% block phpwpcc_head %}
{{ parent() }}

<link rel="stylesheet" type="text/css" href="css/menu.css" />


<script src="js/jquery-ui.custom.min.js" type="text/javascript"></script>
<script src="js/jquery.cookie.js" type="text/javascript"></script>
{% endblock %}
{% block title %}{{ parent() }} - Update Service Configuration {% endblock %}

{% block content %}
{% include 'phpwpcc_menu.tpl' %}
{% for service, config in services %}
{% if loop.index is divisibleby(2)  %}
<div id="service_{{ service }}" class="phpwpcc_service phpwpcc_service_bis" >
{% else %}
<div id="service_{{ service }}" class="phpwpcc_service" >
{% endif %}
<h2 class="phpwpcc_h2">{{ service }} Service Configuration</h2>

<input type="hidden" name="service[]" value="{{ service }}">
<div id="service_{{ service }}_config" class="phpwpcc_service_cfg">
    <input name="nbfile_{{ service }}" type="hidden" value="{{ config.acceptedConfig|length  }}">
    {% for version, files in config.acceptedConfig %}
    <div id="version_{{ service }}_{{loop.index}}_block" class="phpwpcc_version">
            VERSION: <input type="text" name="version_{{ service }}_{{ loop.index }}" value="{{ version }}" />
</div>
<div id="file_{{ service }}" class="phpwpcc_file">
    FILES:
    <textarea name="file_{{ service }}_{{ loop.index }}" rows="4" cols="100">{{ files|join("\r\n") }}</textarea>
</div>
{% endfor %}

</div>
</div>
{% endfor %}
<input type="submit" value="Generate Configuration & PHPUnit Tests" class="phpwpcc_generate">
<input type="hidden" name="formType" value="generate" />
{% endblock %}
{% block javascriptSrc %}

<script src="js/menu.js" type="text/javascript"></script>
{% endblock %}
