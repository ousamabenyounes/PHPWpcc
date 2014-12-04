{% extends "install/phpwpcc_base.tpl" %}
{% block title %}{{ parent() }} - Project Configuration{% endblock %}
{% block content %}
  <form id="myform" class="fs-form fs-form-full" autocomplete="off" action="install.php" method="post">
    <input type="hidden"" value="configureServicesForm" name="nextStep"/>
    <ol class="fs-fields">
       <li>
         <label class="fs-field-label fs-anim-upper" for="projectName" data-info="PHPUnit test file names and class will be prefixed with this information...">Your project name?</label>
         <input class="fs-anim-lower" id="projectName" name="projectName" type="text" placeholder="Project Name" required/>
       </li>

       <li>
         <label class="fs-field-label fs-anim-upper" for="webServiceUrl" data-info="This WebService url will allow configuration updates sharing between all your installed PHPWpcc instance...">WebService Url?</label>
        <input class="fs-anim-lower" id="webServiceUrl" name="webServiceUrl" type="text" placeholder="http://your-phpwpcc-host/ws/" required/>
       </li>
       <li>
         <label class="fs-field-label fs-anim-upper" for="emailAdressList" data-info="Please fill in all email adresse you want to be notified by PHPWpcc errors">Email notification listing</label>
         <textarea class="fs-anim-lower" id="emailAdressList" name="emailAdressList" placeholder="contact@phpwpcc.com"></textarea>
       </li>
    </ol><!-- /fs-fields -->
    <button class="fs-submit" type="submit">Send</button>
   </form>
   
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