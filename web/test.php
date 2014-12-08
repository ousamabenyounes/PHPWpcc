<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="keywords" content="jquery,ui,easy,easyui,web">
    <meta name="description" content="easyui help you build your web page easily!">
    <title>Tree with Checkbox Nodes - jQuery EasyUI Demo</title>

    <script type="text/javascript" src="http://code.jquery.com/jquery-1.6.1.min.js"></script>
    <style>
        /* Visit http://www.menucool.com/horizontal/javascript-menu for source code and other menu CSS templates */

        #sse2
        {
            /*You can decorate the menu's container, such as adding background images through this block*/
            background-color: #666;
            height: 38px;
            padding: 15px;
            border-radius: 8px;
            box-sizing: content-box;
        }
        #sses2
        {
            margin:0 auto;/*This will make the menu center-aligned. Removing this line will make the menu align left.*/
        }
        #sses2 ul
        {
            position: relative;
            list-style-type: none;
            float:left;
            padding:0;margin:0;
        }
        #sses2 li
        {
            float:left;
            list-style-type: none;
            padding:0;margin:0;background-image:none;
        }
        /*CSS for background bubble*/
        #sses2 li.highlight
        {
            background:#990;
            top:0px;
            height:30px;
            border-radius: 8px;
            -moz-border-radius: 8px;
            -webkit-border-radius: 8px;
            z-index: 1;
            position: absolute;
            -ms-filter: "progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#000000')";/*For IE 8*/
            filter: progid:DXImageTransform.Microsoft.Shadow(Strength=1, Direction=135, Color='#000000'); /*For IE 5.5 - 7*/
        }
        #sses2 li a
        {
            height:30px;
            padding-top: 8px;
            margin: 0 20px;/*used to adjust the distance between each menu item. Now the distance is 20+20=40px.*/
            color: white;
            font: normal 12px arial;
            text-align: center;
            text-decoration: none;
            float: left;
            display: block;
            position: relative;
            z-index: 2;
        }
    </style>
</head>
<body>
<div id="sse2">
    <div id="sses2">
        <ul>
            <li><a href="?menu=2&skin=1&p=Javascript-Menus">Javascript Menus</a></li>
            <li><a href="?menu=2&skin=1&p=Horizontal-Menus">Horizontal Menus</a></li>
            <li><a href="?menu=2&skin=1&p=Web-Menus">Web Menus</a></li>
        </ul>
    </div>
</div>

<script>
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
</script>
</body></html>