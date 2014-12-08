{% extends "install/phpwpcc_base.tpl" %}
{% block phpwpcc_head %}
    {{ parent() }}
    <script src="js/jquery-ui.custom.min.js" type="text/javascript"></script>
    <script src="js/jquery.cookie.js" type="text/javascript"></script>

    <link href="css/ui.dynatree.css" rel="stylesheet" type="text/css" id="skinSheet">
    <script src="js/jquery.dynatree.js" type="text/javascript"></script>
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


<form method="post" action="service.php" />

<input type="hidden" value="{{ service }}" id="service" name="service">

<p class="description">
    To enable {{ service }} service on your website, juste check the needed
</p>
<div id="tree3"></div>
<div>Selected keys: <span id="echoSelection3">-</span></div>
<div>Selected root keys: <span id="echoSelectionRootKeys3">-</span></div>
<div>Selected root nodes: <span id="echoSelectionRoots3">-</span></div>



<script>
    $(document).ready(function(){


        var treeData = [
            {% for portail, sites in groupUrl%}
            {title: "Portail {{ portail }}", isFolder: true, key: "{{ portail }}",
             children: [
            {% for siteConf in sites %}
                {% for siteConf in sites %}
                    {% for site, urls in siteConf %}

                    {title: "Site {{ site }}", isFolder: true, key: "{{ site }}" {% if urls is empty %} } {% else %} ,
                    children: [
                    {title: "Page {{ site }}", key: "{{ site }}"},
                        {% for url in urls %}
                    {title: "Page {{ url }}", key: "{{ url }}"},

                        {% endfor %}
                    ]},
                                                                            {% endif %}
            {% endfor %}
        {% endfor %}
    {% endfor %}
        ]
    },
       {% endfor%}
   ];



   $(function(){




            $("#tree3").dynatree({
                checkbox: true,
                selectMode: 3,
                children: treeData,
                onSelect: function(select, node) {
                    // Get a list of all selected nodes, and convert to a key array:
                    var selKeys = $.map(node.tree.getSelectedNodes(), function(node){
                        return node.data.key;
                    });
                    $("#echoSelection3").text(selKeys.join(", "));

                    // Get a list of all selected TOP nodes
                    var selRootNodes = node.tree.getSelectedNodes(true);
                    // ... and convert to a key array:
                    var selRootKeys = $.map(selRootNodes, function(node){
                        return node.data.key;
                    });
                    $("#echoSelectionRootKeys3").text(selRootKeys.join(", "));
                    $("#echoSelectionRoots3").text(selRootNodes.join(", "));
                },
                onDblClick: function(node, event) {
                    node.toggleSelect();
                },
                onKeydown: function(node, event) {
                    if( event.which == 32 ) {
                        node.toggleSelect();
                        return false;
                    }
                },
                // The following options are only required, if we have more than one tree on one page:
//        initId: "treeData",
                cookieId: "dynatree-Cb3",
                idPrefix: "dynatree-Cb3-"
            });


            $("#btnToggleSelect").click(function(){
                $("#tree2").dynatree("getRoot").visit(function(node){
                    node.toggleSelect();
                });
                return false;
            });
            $("#btnDeselectAll").click(function(){
                $("#tree2").dynatree("getRoot").visit(function(node){
                    node.select(false);
                });
                return false;
            });
            $("#btnSelectAll").click(function(){
                $("#tree2").dynatree("getRoot").visit(function(node){
                    node.select(true);
                });
                return false;
            });
            <!-- (Irrelevant source removed.) -->







       /*! Visit www.menucool.com for source code, other menu scripts and web UI controls
        *  Please keep this notice intact. Thank you. */

       var sse2 = function () {
           var rebound = 20; //set it to 0 if rebound effect is not prefered
           var slip, k;
           return {
               buildMenu: function () {
                   var m = document.getElementById('sses2');
                   if (!m) return;
                   var ul = m.getElementsByTagName("ul")[0];
                   m.style.width = ul.offsetWidth + 1 + "px";
                   var items = m.getElementsByTagName("li");
                   var a = m.getElementsByTagName("a");

                   slip = document.createElement("li");
                   slip.className = "highlight";
                   ul.appendChild(slip);

                   var url = document.location.href.toLowerCase();
                   k = -1;
                   var nLength = -1;
                   for (var i = 0; i < a.length; i++) {
                       if (url.indexOf(a[i].href.toLowerCase()) != -1 && a[i].href.length > nLength) {
                           k = i;
                           nLength = a[i].href.length;
                       }
                   }

                   if (k == -1 && /:\/\/(?:www\.)?[^.\/]+?\.[^.\/]+\/?$/.test) {
                       for (var i = 0; i < a.length; i++) {
                           if (a[i].getAttribute("maptopuredomain") == "true") {
                               k = i;
                               break;
                           }
                       }
                       if (k == -1 && a[0].getAttribute("maptopuredomain") != "false")
                           k = 0;
                   }
                   if (k > -1) {
                       slip.style.width = items[k].offsetWidth + "px";
                       //slip.style.left = items[k].offsetLeft + "px";
                       sse2.move(items[k]); //comment out this line and uncomment the line above to disable initial animation
                   }
                   else {
                       slip.style.visibility = "hidden";
                   }

                   for (var i = 0; i < items.length - 1; i++) {
                       items[i].onmouseover = function () {
                           if (k == -1) slip.style.visibility = "visible";
                           if (this.offsetLeft != slip.offsetLeft) {
                               sse2.move(this);
                           }
                       }
                   }

                   m.onmouseover = function () {
                       if (slip.t2)
                           slip.t2 = clearTimeout(slip.t2);
                   };

                   m.onmouseout = function () {
                       if (k > -1 && items[k].offsetLeft != slip.offsetLeft) {
                           slip.t2 = setTimeout(function () { sse2.move(items[k]); }, 50);
                       }
                       if (k == -1) slip.t2 = setTimeout(function () { slip.style.visibility = "hidden"; }, 50);
                   };
               },
               move: function (target) {
                   clearInterval(slip.timer);
                   var direction = (slip.offsetLeft < target.offsetLeft) ? 1 : -1;
                   slip.timer = setInterval(function () { sse2.mv(target, direction); }, 15);
               },
               mv: function (target, direction) {
                   if (direction == 1) {
                       if (slip.offsetLeft - rebound < target.offsetLeft)
                           this.changePosition(target, 1);
                       else {
                           clearInterval(slip.timer);
                           slip.timer = setInterval(function () {
                               sse2.recoil(target, 1);
                           }, 15);
                       }
                   }
                   else {
                       if (slip.offsetLeft + rebound > target.offsetLeft)
                           this.changePosition(target, -1);
                       else {
                           clearInterval(slip.timer);
                           slip.timer = setInterval(function () {
                               sse2.recoil(target, -1);
                           }, 15);
                       }
                   }
                   this.changeWidth(target);
               },
               recoil: function (target, direction) {
                   if (direction == -1) {
                       if (slip.offsetLeft > target.offsetLeft) {
                           slip.style.left = target.offsetLeft + "px";
                           clearInterval(slip.timer);
                       }
                       else slip.style.left = slip.offsetLeft + 2 + "px";
                   }
                   else {
                       if (slip.offsetLeft < target.offsetLeft) {
                           slip.style.left = target.offsetLeft + "px";
                           clearInterval(slip.timer);
                       }
                       else slip.style.left = slip.offsetLeft - 2 + "px";
                   }
               },
               changePosition: function (target, direction) {
                   if (direction == 1) {
                       //following +1 will fix the IE8 bug of x+1=x, we force it to x+2
                       slip.style.left = slip.offsetLeft + Math.ceil(Math.abs(target.offsetLeft - slip.offsetLeft + rebound) / 10) + 1 + "px";
                   }
                   else {
                       //following -1 will fix the Opera bug of x-1=x, we force it to x-2
                       slip.style.left = slip.offsetLeft - Math.ceil(Math.abs(slip.offsetLeft - target.offsetLeft + rebound) / 10) - 1 + "px";
                   }
               },
               changeWidth: function (target) {
                   if (slip.offsetWidth != target.offsetWidth) {
                       var diff = slip.offsetWidth - target.offsetWidth;
                       if (Math.abs(diff) < 4) slip.style.width = target.offsetWidth + "px";
                       else slip.style.width = slip.offsetWidth - Math.round(diff / 3) + "px";
                   }
               }
           };
       } ();

       if (window.addEventListener) {
           window.addEventListener("load", sse2.buildMenu, false);
       }
       else if (window.attachEvent) {
           window.attachEvent("onload", sse2.buildMenu);
       }
       else {
           window["onload"] = sse2.buildMenu;
       }

        });





    });
</script>


<input type="hidden" name="nextStep" value="configureServicesFormStep2"/>
<div class="fs-controls">
    <button class="fs-continue fs-show">Continue</button>
    <nav class="fs-nav-dots fs-show">
        <div class="fs-progress fs-show"></div>
</div>


</form>

{% endblock %}
