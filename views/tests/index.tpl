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
{% autoescape false %}

<ul id="loadbar">
    {% for portail, config in servicePresent %}
    <h3 id="h3_{{ portail }}" onClick='getServicePortailStatus("{{ service }}", "{{ portail }}");'>{{ portail }} </h3>
    <div id="{{ portail }}_block" class="portail_block" >


        {% for fctName, page in config.testMethodNames  %}
        <li data-link="{{ page }}" id="{{ fctName }}"   >
            <div class="bar "></div>
        </li>
        {% endfor %}

        <li id="spaces_{{portail}}" class="addSpace" >
        </li>


        {% set configNp = attribute(serviceNotPresent, portail) %}

        {% for fctName, page in configNp.testMethodNames  %}
        <li data-link="{{ page }}" id="{{ fctName }}"  {% if loop.first %} style="clear: both;"{% endif %} >
        <div class="bar "></div>
        </li>

        {% endfor %}

        <li id="spaces_{{portail}}" class="addSpace">
        <input type="submit" value="Refresh {{ portail }} PHPUnit tests" class="phpwpcc_generate phpwpcc_refresh" onClick="refreshTests('{{ portail }}')">

        </li>
    </div>

    {% endfor %}
</ul>





{% endautoescape %}

{% endblock %}
{% block javascriptSrc %}
{% set urlPos = 0 %}
{% set errorMsgPos = 1 %}

<link rel="stylesheet" type="text/css" href="css/tooltipster.css" />
<script src="js/menu.js" type="text/javascript"></script>
<script>

    function refreshTests(portail) {
        launchPortailPhpunitTests("{{ service }}", portail, 'Present');
        launchPortailPhpunitTests("{{ service }}", portail, 'NotPresent');
    }

    function launchPortailPhpunitTests(service, portail, testType) {
        console.log('launchPortailPhpunitTests: service: ' + service + ' ---- portail:' + portail
                    + ' ---- testType:' + testType);

        var  formData = 'service=' + service + '&portail=' + portail + '&testType=' + testType;  //Name value Pair
        $.ajax({
            url : "ajax/launchPortailTest.php",
            type: "POST",
            data : formData,
            success: function(data, textStatus, jqXHR)
            {
                window.setInterval(function(){
                    getServicePortailStatusAjaxCall(service, portail, testType)
                }, 1000);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                //console.log('launchPortailPhpunitTests::error => ' + errorThrown);
            }
        });
    }

//<img src='images/no-preview.jpg' class='phpwpcc_img_preview'>
//<img src='{{ thumbnailDir }}france2fremissionscain600X800-mini.jpg'  >

    function test_success(testsOkObj, service, testType)
    {
         var PREVIEW_URL = 1;
         var THUMBNAIL_POS = 0;
         var SCREENSHOT_POS = 1;

         for (var testName in testsOkObj) {
             if (testsOkObj.hasOwnProperty(testName)) {
                  //console.log("Key is " + testName + ", value is" + testsOkObj[testName]);
                //  console.log('0' + testsOkObj[testName][0]);
                  //console.log('cache: ' + testsOkObj[testName][1]);

                var previewUrlArray = testsOkObj[testName][PREVIEW_URL];
		console.log(testsOkObj[testName]);
		console.log('idddentifiant: ' + PREVIEW_URL);
                if (typeof previewUrlArray === 'undefined') {
                    previewSrc = "<img src='{{ rootDir }}{{ noPreview }}' class='phpwpcc_img_preview'>";
                } else {
                    previewSrc = "<a href='{{ rootDir }}" + previewUrlArray[SCREENSHOT_POS] + "' target='_blank'>"
                        + "<img src='{{ rootDir }}" + previewUrlArray[THUMBNAIL_POS] + "' class='phpwpcc_img_preview'>"
                        + "</a>";
                }
                var link = $("#" + testName).data("link");		
                var tooltipContent = "<div class='phpwpcc_test_img_div'>" + previewSrc + "</div>"
                    + "<div class='phpwpcc_test_detail_div'>"
                        + "<strong>- Fonction : </strong>" + testName
                        + "<span style='display:block;'></span>"
                        + "<span><strong>- Service: </strong>" + service.toUpperCase()
                        + "<span style='display:block;'></span>"
                        + "<strong>- Test Type: </strong>" + testType
                        + "<span style='display:block;'></span>"
                        + "<strong>- Web Page: </strong><a href='" + link + "' target='_blank'>"
                        + "<span style='display:block;'></span>"
                        + link + "</a></span>"
                    + "</div>";
                $("#" + testName).addClass('success');
                $("#" + testName).tooltipster({
                    interactive: true,
                    contentAsHTML: true,
                    content: $(tooltipContent),
                });

             }
         }


    }


    function test_failed(testsFailedObj, service, testType)
    {
        var ERROR_MESSAGE_POS = 1;
        var PREVIEW_URL = 2;
        for (var testName in testsFailedObj) {
            var testFailedConfig = testsFailedObj[testName];
            var link = $("#" + testName).data("link");
            $("#" + testName).addClass('failed');
            var tooltipContent = "<strong>- Fonction : </strong>" + testName + "<br/>"
                + "<span><strong>- Service: </strong>" + service.toUpperCase() + "<br/>"
                + "<strong>- Test Type: </strong>" + testType + "<br/>"
                + "<strong>- Web Page: </strong><a href='" + link + "' target='_blank'>"
                + link + "</a><br/>"
                + "<br/><strong>PHPUnit Error Message: </strong><br/>"
                + testFailedConfig[ERROR_MESSAGE_POS]  + "</span>";
            $("#" + testName).tooltipster({
                interactive: true,
                contentAsHTML: true,
                content: $(tooltipContent)
            });
        }
    }


    function getServicePortailStatusAjaxCall(service, portail, testType) {
        //console.log('getServicePortailStatusAjaxCall: service: ' + service + ' ---- portail:' + portail
        //                           + ' ---- testType:' + testType);
        var  formData = 'service=' + service + '&portail=' + portail + '&testType=' + testType;
        $.ajax({
                url : "ajax/getServicePortailStatus.php",
                type: "POST",
                data : formData,
                success: function(data, textStatus, jqXHR)
                {
                    //data - response from server
                    if (true === data.success)
                    {
                        test_success(data.content.testsOk, service, testType);
                        test_failed(data.content.testsFailed, service, testType);
                    } else {
                        console.log(data.content);
                    }

                },
                error: function (jqXHR, textStatus, errorThrown)
                {

                }
        });
    }


    function getServicePortailStatus(service, portail)
    {
        getServicePortailStatusAjaxCall(service, portail, 'Present');
        getServicePortailStatusAjaxCall(service, portail, 'NotPresent');
    }

    getServicePortailStatus("{{service}}", "{{ firstPortail }}");

    $(document).ready(function() {
        $( "#loadbar" ).accordion();
        $('.tooltip').tooltipster();




    });
</script>
{% endblock %}
