<?php
$phpwpcc_config = array(
                'projectName' => '{{ projectName }}',
                'webServiceUrl' => '{{ webServiceUrl }}',
                'emailAdressList' => array(
                    {% for email in emailAdressList %}
                        '{{ email }}',
                    {% endfor %}
                ),
);
?>
