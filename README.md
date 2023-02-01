# postal-wp
Postal integration into WordPress

It uses the AtelliTech\Postal library to send emails via the self hosted Postal API
Please note  this is a WIP< word in progress>

Known issues:
    - wordpress settings page is blank
    - emails are not sent
    - needs more functionality


To do list:

Skipping certain emails:
Add a new setting to allow administrators to specify the headers that should be used to skip sending emails.
In the main plugin file, check for these headers in each email before sending it through SMTP. If the header is present, skip sending the email.
Setting PHPMailer timeout value:
Add a new setting to allow administrators to specify the timeout value.
In the main plugin file, set the timeout value using the $mail->Timeout property of the PHPMailer object before sending the email.
Adding support for DKIM headers:
Add a new setting to allow administrators to specify the DKIM header values, such as the domain name and the selector.
In the main plugin file, add the DKIM header to the email using the $mail->DKIM_domain and $mail->DKIM_selector properties of the PHPMailer object before sending the email.
Setting the log file location and rotation frequency:
Add a new setting to allow administrators to specify the log file location and rotation frequency.
In the main plugin file, use the specified log file location and rotation frequency to write the log entries.
Excluding certain email addresses or domains from being logged:
Add a new setting to allow administrators to specify the email addresses or domains to be excluded from logging.
In the main plugin file, check for these email addresses or domains in each email before logging it. If the email address or domain matches, skip logging the email.
Customizing the log message format:
Add a new setting to allow administrators to specify the log message format.
In the main plugin file, use the specified log message format to write the log entries.
Choosing the log level:
Add a new setting to allow administrators to choose the log level.
In the main plugin file, use the specified log level to determine the amount of information that should be logged.