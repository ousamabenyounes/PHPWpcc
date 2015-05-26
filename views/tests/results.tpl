{% extends "base/tests.tpl" %}
{% block phpwpcc_head %}
{{ parent() }}


<link rel="stylesheet" type="text/css" href="css/menu.css" />
<link rel="stylesheet" type="text/css" href="css/tests.css" />
<link rel="stylesheet" type="text/css" href="css/phpwpcc.css" />
<link rel="stylesheet" loadbartype="text/css" href="css/tooltipster.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui.1.11.2.min.css">
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery-ui-1.11.2.js"></script>
<script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>
<script type="text/javascript" src="js/lazyload.min.js"></script>


{% endblock %}
{% block title %}{{ parent() }} - {{ service|upper }}  Status {% endblock %}
{% block content %}

{% include 'menu/tests.tpl' %}
<br/>
<br/>
<center>
Here we'll find main tests results...
</center>
{% endblock %}
{% block javascriptSrc %}
{% set urlPos = 0 %}
{% set errorMsgPos = 1 %}

<link rel="stylesheet" type="text/css" href="css/tooltipster.css" />
<script src="js/menu.js" type="text/javascript"></script>
<script>
{% endblock %}
