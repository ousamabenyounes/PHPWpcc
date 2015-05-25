{% extends "base/services.tpl" %}
{% block phpwpcc_head %}
{{ parent() }}

<link rel="stylesheet" type="text/css" href="css/menu.css" />
<link rel="stylesheet" type="text/css" href="css/tests.css" />
<link rel="stylesheet" loadbartype="text/css" href="css/tooltipster.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />


<script src="js/jquery-ui.custom.min.js" type="text/javascript"></script>
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery-ui-1.11.2.js"></script>

{% endblock %}
{% block title %}{{ parent() }} Urls - Update Portails / Websites Configuration
{% endblock %}
{% block content %}

{% include 'menu/services.tpl' %}

<div id="dialog-confirm" title="Empty the recycle bin?" style="display:none">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
    These item will be permanently deleted and cannot be recovered.</p>
</div>
<div class="phpwpcc_add_service">
    <input type="text" id="newPortail" placeholder="New portail" >
    <input type="submit" value="Add a new portail"  onClick="addPortail();" class="phpwpcc_add_service_btn">
</div>

<form method=post action="groupUrlUpdate.php">


<ul id="loadbar">

{% for portail, websiteConf in groupurl %}

<h3 id="portail_{{ portail }}">
    <img id="{{ portail }}_remove" src="images/delete.png"  class="phpwpcc_delete_img_service" onClick="removePortail('{{ portail }}');">
    Portail {{ portail }}
    <img id="{{ portail }}_add" src="images/add.png"  class="phpwpcc_add_img" onClick="addUrl('{{ portail }}');">
</h3>
<div id="portail_{{ portail }}_block">
    <input type="hidden" id="portails[]" name="portails[]" value="{{ portail }}">
    <div id="portail_{{ portail }}_config" class="phpwpcc_service_cfg">
        <input id="nbWebsites_{{ portail }}"  name="nbWebsites_{{ portail }}" type="hidden" value="{{ websiteConf|length  }}">
        {% for website, pages in websiteConf %}
        <div id="website_{{ portail }}_{{loop.index}}_div">
                <img id="{{ portail }}_{{ loop.index }}" src="images/delete.png"  class="phpwpcc_delete_img" onClick="removeWebsite('{{ portail }}', '{{ loop.index }}');">
                <div id="portail_{{ portail }}_{{ loop.index }}_block" class="phpwpcc_version">
                    Website <input type="text" id="website_{{ portail }}_{{ loop.index }}" name="website_{{ portail }}_{{ loop.index }}" value="{{ website }}" class="phpwpcc_website"/>
                </div>
                <div id="pages_{{ portail }}_{{ loop.index }}" class="phpwpcc_file">

                         Pages  <textarea id="pages_{{ portail }}_{{ loop.index }}_text" name="pages_{{ portail }}_{{ loop.index }}_text" class="phpwpcc_websites">{{ pages|keys|join("\r\n") }}</textarea>
                </div>
        </div>
    {% endfor %}
    </div>
</div>





{% endfor %}
</ul>

<input type="submit" value="Update Portails / Websites Configuration" class="phpwpcc_generate full_size">
<input type="hidden" id="nextStep" name="nextStep" value="updateGroupUrl" />

</form>


<br/>
<br/>
{% endblock %}



{% block javascriptSrc %}
<script src="js/menu.js" type="text/javascript"></script>
<script>


    function ucfirst(str) {
        //  discuss at: http://phpjs.org/functions/ucfirst/
        // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // bugfixed by: Onno Marsman
        // improved by: Brett Zamir (http://brett-zamir.me)
        //   example 1: ucfirst('kevin van zonneveld');
        //   returns 1: 'Kevin van zonneveld'

        str += '';
        var f = str.charAt(0)
            .toUpperCase();
        return f + str.substr(1);
    }

    function addPortail()
    {
        newPortail = ucfirst($("#newPortail").val().trim());
        if ('' === newPortail) {
            alert('Portail Field is empty');
            return;
        }
        $("#newPortail").val("")
        var htmlToAdd = '<h3 id="portail_' + newPortail + '" class="ui-accordion-header ui-state-default ui-accordion-icons ui-corner-all" role="tab" aria-controls="portail_' + newPortail + '_block" aria-selected="false" aria-expanded="false" tabindex="-1">'
        + '     <img id="' + newPortail + '_remove" src="images/delete.png" class="phpwpcc_delete_img_service" onClick="removePortail(\'' + newPortail + '\')";>'
        + '     ' + newPortail
        + '     <img id="' + newPortail + '_add" src="images/add.png" class="phpwpcc_add_img" onClick="addUrl(\'' + newPortail + '\');">'
        + '</h3>'
        + '<div id="portail_' + newPortail + '_block"  class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active" aria-labelledby="portail_' + newPortail + '" role="tabpanel" aria-hidden="false" style="display: block;">'
        + '     <input type="hidden" id="portails[]"  name="portails[]" value="' + newPortail + '">'
        + '     <div id="portail_' + newPortail + '_config" class="phpwpcc_service_cfg">'
        + '         <input id="nbWebsites_' + newPortail + '"  name="nbWebsites_' + newPortail + '" type="hidden" value="1">'
        + '         <div id="website_' + newPortail + '_1_div">'
        + '             <img id="' + newPortail + '_1" src="images/delete.png"  class="phpwpcc_delete_img" onClick="removeWebsite(\'' + newPortail + '\', \'1\');">'
        + '             <div id="website_' + newPortail + '_1_block" class="phpwpcc_version">'
        + '                 Website <input type="text" id="website_' + newPortail  + '_1"  name="website_' + newPortail  + '_1" value="" class="phpwpcc_website"/>'
        + '             </div>'
        + '             <div id="pages_' + newPortail + '_1" class="phpwpcc_file">'
        + '                 Pages <textarea id="pages_' + newPortail + '_1_text"  name="pages_' + newPortail + '_1_text" class="phpwpcc_websites"></textarea>'
        + '             </div>'
        + '         </div>';
        + '     </div>'
        + '</div>';


        $("#loadbar").prepend(htmlToAdd);
        $('#portail_' + newPortail + '_block').effect('highlight', {}, 200, function() {
                $(this).fadeIn('fast', function(){
            });
        });

        $( "#loadbar" ).accordion({heightStyle: "content"} );

    }

    function updateNbWebSite(nbFileId, toAdd)
    {
        newVal = parseInt($("#" + nbFileId).val()) + parseInt(toAdd);
        $("#" + nbFileId).val(newVal);
        return newVal;
    }

    function addUrl(portail)
    {
        updateNbWebSite("nbWebsites_" + portail, 1);
        var nbWebsites = parseInt($("#nbWebsites_" + portail).val());
        var htmlToAdd = '<div id="website_' + portail + '_' + nbWebsites + '_div"> '
        + '     <img id="' + portail + '_' + nbWebsites + '" src="images/delete.png"  class="phpwpcc_delete_img" onClick="removeWebsite(\'' + portail + '\', \'' + nbWebsites + '\');">'
        + '     <div id="website_' + portail + '_' + nbWebsites + '_block" class="phpwpcc_version">'
        + '         Webiste <input type="text" id="website_' + portail + '_' + nbWebsites + '" name="website_' + portail + '_' + nbWebsites + '" class="phpwpcc_website"/>'
        + '     </div>'
        + '     <div id="pages_' + portail + '_' + nbWebsites + '" class="phpwpcc_file">'
        + '         Pages <textarea id="pages_' + portail + '_' + nbWebsites + '_text" name="pages_' + portail + '_' + nbWebsites + '_text" class="phpwpcc_websites"></textarea>'
        + '     </div>'
        + '</div>';

        $("#portail_" + portail + "_config").prepend(htmlToAdd);

        $('#website_' + portail + '_' + nbWebsites + '_div').effect('highlight', {}, 200, function() {
                $(this).fadeIn('fast', function(){
            });
        });
    }

    function removeWebsite(portail, loopIndex)
    {
        loopIndex = parseInt(loopIndex);
        var divId = portail + '_' + loopIndex;
        var websiteVal = $('#website_' + portail + '_' + loopIndex).val();
        if (typeof websiteVal === 'undefined') {
            websiteVal = 'this website ';
        }
        $( "#dialog-confirm" ).dialog({
            resizable: false,
            height:230,
            width: 450,
            modal: true,
            title: 'Remove ' + websiteVal + ' from ' + portail + ' portail?',
            buttons: {
                "Remove this website": function() {
                    $( this ).dialog( "close" );
                    removeItem($("#website_" + divId + '_div'));
                    newVal = updateNbWebSite("nbWebsites_" + portail, -1);
                    if (0 === newVal) {
                        removeItem($("#portail_" + portail));
                        removeItem($("#portail_" + portail + '_block'));
                    }
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    }

    function removePortail(portail)
    {
        $( "#dialog-confirm" ).dialog({
            resizable: false,
            height:250,
            width: 350,
            modal: true,
            title: 'Remove the ' + portail + ' portail',
            buttons: {
                "Remove this portail": function() {
                    $( this ).dialog( "close" );
                    removeItem($("#portail_" + portail));
                    removeItem($("#portail_" + portail + '_block'));
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    }

    function removeItem(item)
    {
        item.effect('highlight', {}, 200, function() {
                $(this).fadeOut('fast', function(){
                $(this).remove();
            });
        });
    }

    $(document).ready(function() {
        $( "#loadbar" ).accordion({heightStyle: "content"} );
    });

    $('textarea').focus(function () {
        var text = $(this).val();
        var lines = text.split(/\r|\r\n|\n/);
        var newHeight = lines.length + 1;

        $(this).animate({ height: newHeight + "em" }, 500);
    });
</script>

{% endblock %}