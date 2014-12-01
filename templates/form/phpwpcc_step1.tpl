{% extends "form/phpwpcc_base_s1.tpl" %}
{% block content %}

  <form id="myform" class="fs-form fs-form-full" autocomplete="off" action="step1.php" method="post">
    <ol class="fs-fields">
       <li>
         <label class="fs-field-label fs-anim-upper" for="q1" data-info="PHPUnit test file names and class will be prefixed with this information...">You're project name?</label>
         <input class="fs-anim-lower" id="q1" name="q1" type="text" placeholder="Project Name" required/>
       </li>
       <li>
         <label class="fs-field-label fs-anim-upper" for="q2" data-info="Number of services you want to check in your generated PHPUnit test">How many services do you need to check?</label>
         <input class="fs-mark fs-anim-lower" id="q2" name="q2" type="number" placeholder="2" step="1" min="1" required/>
       </li>
       <li>
         <label class="fs-field-label fs-anim-upper" for="q3" data-info="This WebService url will allow configuration updates sharing between all your installed PHPWpcc instance...">WebService Url?</label>
        <input class="fs-anim-lower" id="q3" name="q3" type="text" placeholder="http://your-phpwpcc-host/ws/" required/>
       </li>
       <li>
         <label class="fs-field-label fs-anim-upper" for="q4" data-info="Please fill in all email adresse you want to be notified by PHPWpcc errors">Email notification listing</label>
         <textarea class="fs-anim-lower" id="q4" name="q4" placeholder="contact@phpwpcc.com"></textarea>
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