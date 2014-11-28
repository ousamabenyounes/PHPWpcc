{% extends "phpwpcc_base.tpl" %}
{% block content %}

    <div class="input_fields_wrap">
      <button class="add_field_button phpwpcc_generate">Add another service</button>
      <br/>
      <div  id="step1_configuration" class="phpwpcc_service">
        Service: <input type="text" name="service[]" /> 
	Number of accepted version: <input type="text" name="nbConfig[]"  size="1"/>
      </div>
    </div>
    
    <input type="submit" value="Generate Configuration Step 2/3" class="phpwpcc_generate"/>
    <input type="hidden" name="formType" value="create"/>


{% endblock %}

