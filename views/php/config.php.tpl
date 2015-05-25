<?php
$phpwpcc_config = array(
		'namespace' => 'PHPWpcc',
                'projectName' => '{{ projectName }}',
                'mailFrom' => '{{ mailFrom }}',
                'smtpHost' => '{{ smtpHost }}',
                'smtpPort' => '{{ smtpPort }}',
		'smtpSsl' => '{{ smtpSsl }}', 
                'smtpLogin' => '{{ smtpLogin }}',
                'smtpPassword' => '{{ smtpPassword }}',
                'mailsTo' => array(
                {% for email in mailsTo %}
                    "{{ email|trim()|nl2br()|split("\n")|join() }}",
                {% endfor %}
                ),
                'cachePurge' => '{{ cachePurge }}',
                'configPurge' => '{{ configPurge }}',
);
?>
