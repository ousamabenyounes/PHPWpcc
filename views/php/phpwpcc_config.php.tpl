<?php
$phpwpcc_config = array(
                'projectName' => '{{ projectName }}',
                'webServiceUrl' => '{{ webServiceUrl }}',
                'emailAdressList' => array(
                {% for email in emailAdressList %}
                    "{{ email|trim()|nl2br()|split("\n")|join() }}",
                {% endfor %}
                ),
);
?>
