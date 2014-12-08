{% extends "install/phpwpcc_base.tpl" %}
{% block phpwpcc_head %}
    {{ parent() }}

    <link rel="stylesheet" type="text/css" href="css/menu.css" />

    <link href="css/.css" rel="stylesheet" type="text/css" id="skinSheet">
    <link href="css/ui.dynatree.css" rel="stylesheet" type="text/css" id="skinSheet">

    <script src="js/jquery-ui.custom.min.js" type="text/javascript"></script>
    <script src="js/jquery.cookie.js" type="text/javascript"></script>
    <script src="js/jquery.dynatree.js" type="text/javascript"></script>
    <script src="js/jquery-ui.custom.min.js" type="text/javascript"></script>

{% endblock %}
{% block title %}{{ parent() }} - {{ service|upper }} Service Configuration {% endblock %}
{% block content %}

{% include 'phpwpcc_menu.tpl' %}



<form method="post" action="service.php" id="attachUrlWithServices"/>

<input type="hidden" value="{{ service }}" id="service" name="service">
<input type="hidden" name="nextStep" value="generateAttachedUrlWithServices"/>
<input type="hidden" id="choosenUrl" name="choosenUrl"/>

<p class="description">
    To enable {{ service }} service on your website, just check the needed
</p>
<div id="tree3"></div>


<div class="fs-controls">
    <button class="fs-continue fs-show">Continue</button>
    <nav class="fs-nav-dots fs-show">
        <div class="fs-progress fs-show"></div>
</div>


</form>

{% endblock %}
{% block javascriptSrc %}
<script>





var treeData = [
{% for portal, sites in groupUrl%}
    {title: "Portal: {{ portal }} ", isFolder: true, key: "{{ portail }}",
        children: [
        {% for siteUrl, urls in sites %}
            {title: " WebSite: {{ siteUrl }} ", isFolder: true, key: "{{ siteUrl }}" {% if urls is empty %} } {% else %} ,
            children: [
                {% for url, config in urls %}
                    {title: "Page: {{ url }}", key: "{{ url }}"},
                {% endfor %}
            ]},
            {% endif %}
{% endfor %}
]
},
       {% endfor%}
   ];



</script>
<script src="js/dynatree.conf.js" type="text/javascript"></script>
<script src="js/menu.js" type="text/javascript"></script>

{% endblock %}
