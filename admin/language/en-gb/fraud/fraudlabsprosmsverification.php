<?php
// Heading
$_['heading_title']              = 'FraudLabs Pro SMS Verification';

// Text
$_['text_extension']             = 'Extensions';
$_['text_success']               = 'Success: You have modified FraudLabs Pro SMS Verification Settings!';
$_['text_edit']                  = 'Settings';
$_['text_signup']                = 'FraudLabs Pro SMS Verification helps you to verify the user\'s mobile phone number by using the SMS verification technology. It triggers SMS verification when an order was flagged for <strong>REVIEW</strong> by FraudLabs Pro which will happen after the checkout process. The system will email the user providing them with the link to complete the SMS verification process.<br/><br/>Please visit <a href="https://www.fraudlabspro.com/developer/reference/country-codes-sms" target="_blank">https://www.fraudlabspro.com/developer/reference/country-codes-sms</a> to learn more about the supported countries.<br/><br/><i>Please note that you have to install <u><a href="https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=40314" target="_blank" class="alert-link">FraudLabs Pro extension</a></u> before enabling this feature.</i>';
$_['text_information']           = 'Please visit <a href="https://www.fraudlabspro.com/resources/categories/opencart" target="_blank">FraudLabs Pro Articles & Tutorials</a> page to learn more about the services.<br/><br/>Please <a href="https://www.fraudlabspro.com/contact" target="_blank">contact us</a> if any help is needed.';

// Entry
$_['entry_status']                  = 'Status';
$_['entry_key']                     = 'API Key';
$_['entry_sms_instruction']         = 'SMS Verification Instruction';
$_['entry_sms_template']            = 'SMS Message Content';
$_['entry_sms_email_subject']       = 'Email Subject';
$_['entry_sms_email_content']       = 'Email Body';
$_['entry_sms_otp_timeout']         = 'SMS OTP Timeout';
$_['entry_sms_tel_cc']              = 'Default Country Code For SMS Sending';
$_['entry_msg_otp_success']         = 'OTP Sent Succesfully Message';
$_['entry_msg_otp_fail']            = 'OTP Sent Failed Message';
$_['entry_msg_invalid_phone']       = 'Invalid Phone Number Message';
$_['entry_msg_invalid_otp']         = 'Invalid OTP Message';
$_['entry_debug_status']            = 'Save Debug Log';

// Help
$_['help_sms_key']               = 'You can sign up for a free 10 SMS credits and get the API key if you do not have one.';
$_['help_sms_instruction']       = 'Messages to brief the user about this SMS verification and what needs to be done to complete the verification.';
$_['help_sms_template']          = 'The SMS text message to be sent to the user\'s mobile phone. You must include the {otp} tag which will contain the auto-generated OTP code.';
$_['help_sms_email_subject']     = 'Subject or title of the mail. Leave it blank for default email subject.';
$_['help_sms_email_content']     = 'Email body. You must include the {email_verification_link} tag which will be automatically replaced with the link for the SMS verification process. Leave it blank for default email body.';
$_['help_sms_otp_timeout']       = 'OTP validtity timeout. The default value is 3600 seconds (1 hour) and the maximum value is 86400 seconds (24 hours).';
$_['help_msg_otp_success']       = 'Messages to show the user when the OTP is sent successfully to the phone number. You must include the {phone} tag which will be replaced by the user\'s phone number. Leave it blank for default message.';
$_['help_msg_otp_fail']          = 'Messages to show the user when the OTP is sent failed to the phone number. You must include the {phone} tag which will be replaced by the user\'s phone number. Leave it blank for default message.';
$_['help_msg_invalid_phone']     = 'Messages to show the user when invalid phone number is entered. Leave it blank for default message.';
$_['help_msg_invalid_otp']       = 'Messages to show the user when invalid OTP is entered. Leave it blank for default message.';
$_['help_debug_status']          = 'Enable or disable debug log for development purposes.';

// Error
$_['error_permission']           = 'Warning: You do not have permission to modify FraudLabs Pro SMS Verification settings!';
$_['error_key']                  = 'API Key Required!';
$_['error_sms_template']         = 'The <strong>{otp}</strong> tag must be included in the SMS Message Content!';
$_['error_sms_email_content']    = 'The <strong>{email_verification_link}</strong> tag must be included in the Email Body!';
$_['error_msg_otp_success']      = 'The <strong>{phone}</strong> tag must be included in the OTP Sent Succesfully Message!';
$_['error_msg_otp_fail']         = 'The <strong>{phone}</strong> tag must be included in the OTP Sent Failed Message!';