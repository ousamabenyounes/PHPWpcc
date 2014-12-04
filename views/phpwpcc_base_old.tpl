<!DOCTYPE html>
<html>
    <head>
      <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
        {% block head %}
	<link type="text/css" rel="stylesheet" href="css/phpwpcc.css" media="all" />
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="js/addRemove.js"></script>	
        <title>PHPWpcc - PHPWebPageContent Configuration Generator</title>
        {% endblock %}
    </head>
    <body>
      <div class="phpwpcc_title">
        PHPWpcc - PHPWebPageContent Configuration Generator
      </div>
       <div id="content">
	<form method="post" action="index.php" />
	   {% block content %}{% endblock %}
        </form>
        </div>
        <div id="footer" class="phpwpcc_footer">
            {% block footer %}
                &copy; Copyright 2014 by <a href="https://github.com/ousamabenyounes/PHPWpcc">Ben Younes Ousama</a>.
            {% endblock %}
        </div>
    </body>
</html>
