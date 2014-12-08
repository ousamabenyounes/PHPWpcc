{% extends "install/phpwpcc_base.tpl" %}
{% block phpwpcc_head %}
    {{ parent() }}
    <script src="js/jquery-ui.custom.min.js" type="text/javascript"></script>
    <script src="js/jquery.cookie.js" type="text/javascript"></script>


{% endblock %}
{% block title %}{{ parent() }} - {{ service|upper }} Service Configuration

<div id="sse2">
    <div id="sses2">
        <ul>
            <li><a href="?menu=2&skin=1&p=Javascript-Menus">Javascript Menus</a></li>
            <li><a href="?menu=2&skin=1&p=Horizontal-Menus">Horizontal Menus</a></li>
            <li><a href="?menu=2&skin=1&p=Web-Menus">Web Menus</a></li>
        </ul>
    </div>
</div>

{% endblock %}
{% block content %}




<script>
    $(document).ready(function(){









    });
</script>


<input type="hidden" name="nextStep" value="configureServicesFormStep2"/>
<div class="fs-controls">
    <button class="fs-continue fs-show">Continue</button>
    <nav class="fs-nav-dots fs-show">
        <div class="fs-progress fs-show"></div>
</div>




{% endblock %}
