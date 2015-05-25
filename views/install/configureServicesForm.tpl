{% extends "base/services.tpl" %}
{% block title %}{{ parent() }} - Service Configuration{% endblock %}
{% block content %}
<div id="content" class="phpwpcc_serviceform">
    <form method="post" action="install.php" />
    <div class="input_fields_wrap">
      <button class="add_field_button phpwpcc_generate">Add another service</button>
    </div>
      <div  id="step1_configuration" class="phpwpcc_service">
        Service: <input type="text" name="service[]" /> 
	Number of accepted config: <input type="text" name="nbConfig[]"  size="1"/>
      </div>
    <input type="hidden" name="nextStep" value="configureServicesFormStep2"/>
    <div class="fs-controls">
        <button class="fs-continue fs-show">Continue</button>
        <nav class="fs-nav-dots fs-show">
        <div class="fs-progress fs-show"></div>
    </div>

    </form>
</div>
{% endblock %}

