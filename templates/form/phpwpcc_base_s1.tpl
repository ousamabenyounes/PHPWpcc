<!DOCTYPE html>
<html lang="en" class="no-js">
  
  <head>
    {% block head %}
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <title>PHPWpcc Configuration</title>
    <meta name="author" content="Ousama Ben Younes" />
    <link rel="stylesheet" type="text/css" href="css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="css/demo.css" />
    <link rel="stylesheet" type="text/css" href="css/component.css" />

    <script src="js/modernizr.custom.js"></script>
    <script src="js/fullscreenForm.js"></script>
    {% endblock %}

  </head>
  <body>

  <div class="container">

  <div class="fs-form-wrap" id="fs-form-wrap">
    <div class="fs-title">
      <h1>PHPWpcc Configuration</h1>
    </div>

    {% block content %}
    {% endblock %}


   </div><!-- /fs-form-wrap -->
 </div><!-- /container -->

 </body>
</html>
