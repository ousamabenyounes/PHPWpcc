{% extends "install/phpwpcc_base.tpl" %}
{% block phpwpcc_head %}
{{ parent() }}

<link rel="stylesheet" type="text/css" href="css/menu.css" />

<link href="css/ui.dynatree.css" rel="stylesheet" type="text/css" id="skinSheet">

<script src="js/jquery-ui.custom.min.js" type="text/javascript"></script>
<script src="js/jquery.cookie.js" type="text/javascript"></script>
<script src="js/jquery.dynatree.js" type="text/javascript"></script>

{% endblock %}
{% block title %}{{ parent() }} - {{ service|upper }} Service Configuration {% endblock %}
{% block content %}

{% include 'phpwpcc_menu.tpl' %}



<form method="post" action="service.php?p={{ service }}" id="attachUrlWithServices"/>

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
{title: "Portal: {{ portal }} [ {{ sites|length }} WebSites ]", isFolder: true, key: "{{ portail }}",
    children: [
{% for webSite, pages in sites %}
{title: " WebSite: {{ webSite }}  [ {{ pages|length }} Web Pages ]", isFolder: true, key: "{{ webSite }}" {% if pages is empty %} } {% else %} ,
children: [
{% for page, pageServices in pages %}
{title: "Page: {{ page }}", key: "{{ page }}" {% if service in pageServices %}, select: true {% endif %}},
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
