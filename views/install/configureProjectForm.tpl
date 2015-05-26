{% extends "base/services.tpl" %}
{% block title %}{{ parent() }} - Project Configuration
{% endblock %}
{% block content %}

{% include 'menu/services.tpl' %}

  <form id="myform" class="fs-form fs-form-full" autocomplete="off" action="install.php" method="post">
    <input type="hidden" value="configureServicesForm" name="nextStep"/>
    <ol class="fs-fields">

       <li>
         <label class="fs-field-label fs-anim-upper" for="projectName" data-info="PHPUnit test file names and class will be prefixed with this information...">Your project name?</label>
         <input class="fs-anim-lower" id="projectName" name="projectName" type="text" placeholder="Project Name" value="{{ config.projectName }}" required/>
       </li>

       <li>
        <label class="fs-field-label fs-anim-upper" for="cachePurge" data-info="How many number of cache backup do you want to keep?">Number of cache backup available?</label>
        <input class="fs-mark fs-anim-lower" id="cachePurge" name="cachePurge" type="number" placeholder="10" step="1" min="5" value="{{ config.cachePurge }}">
       </li>

       <li>
        <label class="fs-field-label fs-anim-upper" for="configPurge" data-info="How many number of config file version do you want to keep?">Number of config file version available?</label>
        <input class="fs-mark fs-anim-lower" id="configPurge" name="configPurge" type="number" placeholder="10" step="1" min="5" value="{{ config.configPurge }}">
       </li>
       
       <li>
         <label class="fs-field-label fs-anim-upper" for="mailFrom" data-info="Will set the FROM field when sending test report by mail...">From Email</label>
         <input class="fs-anim-lower" id="mailFrom" name="mailFrom" type="text" placeholder="From Email" value="{{ config.mailFrom }}" required/>
       </li>


       <li>
         <label class="fs-field-label fs-anim-upper" for="smtpHost" data-info="Your Smtp Server address used to send email...">Smtp Address</label>
         <input class="fs-anim-lower" id="smtpHost" name="smtpHost" type="text" placeholder="smtp.google.com" value="{{ config.smtpHost }}" required/>
       </li>




       <li>
         <label class="fs-field-label fs-anim-upper" for="smtpPort" data-info="Smtp Port...">Smtp Port</label>
         <input class="fs-anim-lower" id="smtpPort" name="smtpPort" type="text" placeholder="465" value="{{ config.smtpPort }}" />
       </li>





       <li>
         <label class="fs-field-label fs-anim-upper" for="smtpLogin" data-info="Smtp Login on your smtp server...">Smtp Login</label>
         <input class="fs-anim-lower" id="mailLogin" name="smtpLogin" type="text" placeholder="Your smtp login" value="{{ config.smtpLogin }}" />
       </li>


       <li>
         <label class="fs-field-label fs-anim-upper" for="smtpPassword" data-info="Smtp password on your smtp server...">Smtp Password</label>
         <input class="fs-anim-lower" id="mailPassword" name="smtpPassword" type="password" placeholder="Your smtp password" value="{{ config.smtpPassword }}" />
       </li>


       <li>
         <label class="fs-field-label fs-anim-upper" for="mailsTo" data-info="Please fill in all email adresse you want to be notified by PHPWpcc errors">Email notification listing</label>
         <textarea class="fs-anim-lower" id="mailsTo" name="mailsTo" placeholder="contact@phpwpcc.com">{% for email in config.mailsTo %}
{{ email }}
{% endfor %}</textarea>
       </li>

    </ol><!-- /fs-fields -->
    <button class="fs-submit" type="submit">Send</button>
   </form>
             {% endblock %}
 {% block javascriptSrc %}
 <script src="js/menu.js" type="text/javascript"></script>
   <script src="js/classie.js"></script>
   <script src="js/fullscreenForm.js"></script>
<script>
  (function() {
  	      var formWrap = document.getElementById( 'fs-form-wrap' );
	      [].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {
	      new SelectFx( el, {
			stickyPlaceholder: false,
			onChange: function(val){
			document.querySelector('span.cs-placeholder').style.backgroundColor = val;
			}															});
 } );
  new FForm( formWrap, {															    	     onReview : function() {
      	     	       classie.add( document.body, 'overview' ); // for demo purposes only
     		      }
																		} );
 })();
</script>
{% endblock %}