<?php
namespace Opencart\Catalog\Controller\Extension\Fraudlabsprosmsverification\Fraud;
class Fraudlabsprosmsverification extends \Opencart\System\Engine\Controller {
	public function index(): int {
		// Do not perform fraud SMS verification if FraudLabs Pro SMS Verification is disabled.
		if (!$this->config->get('fraud_fraudlabsprosmsverification_status')) {
			$this->write_debug_log('FraudLabs Pro SMS Verification is disabled. SMS Verification will not be performed.');
			return 0;
		}

		$this->load->language('extension/fraudlabsprosmsverification/fraud/fraudlabsprosmsverification');

		// Set the title of your web page
		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		];

		// Get "heading_title" from language file
		$data['heading_title'] = $this->language->get('heading_title');

		// All the necessary page elements
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$instruction = (null !== $this->config->get('fraud_fraudlabsprosmsverification_sms_instruction')) ? $this->config->get('fraud_fraudlabsprosmsverification_sms_instruction') : 'You are required to verify your phone number by completing the SMS verification process. Please enter your phone number (with country code) to get the OTP message.';

		$id = (isset($this->request->get['orderid'])) ? $this->request->get['orderid'] : '';
		$code = (isset($this->request->get['code'])) ? $this->request->get['code'] : '';
		$phone = (isset($this->request->get['phone'])) ? $this->request->get['phone'] : '';

		if (empty($id)) {
			$data['my_style'] = "style='display: none;'";
			$data['content_error'] = $this->language->get('content_empty_data');
		} else if (empty($code)) {
			$data['my_style'] = "style='display: none;'";
			$data['content_error'] = $this->language->get('content_empty_code');
		} else {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fraudlabsprosmsverification` WHERE order_id = '" . $id . "';");
			$emailCode = $query->row['fraudlabspro_sms_email_code'];
			$emailSms = $query->row['fraudlabspro_sms_email_sms'];
			if ($emailSms == 'VERIFIED') {
				$data['my_style'] = "style='display: none;'";
				$data['content_error'] = $this->language->get('content_phone_verified');
			} elseif ($emailCode == $code) {
				$data['my_style'] = $data['content_error'] = '';
				$data['flp_sms_instruction'] = $instruction;
				$data['flp_sms_tel_cc'] = $this->config->get('fraud_fraudlabsprosmsverification_sms_tel_cc');
				$data['flp_sms_order_id'] = $id;
				$data['flp_sms_code'] = $code;
			} else {
				$data['my_style'] = "style='display: none;'";
				$data['content_error'] = $this->language->get('content_invalid_code');
			}
		}
		$data['flp_sms_msg_otp_success'] = ($this->config->get('fraud_fraudlabsprosmsverification_msg_otp_success') == '') ? 'A SMS containing the OTP (One Time Passcode) has been sent to {phone}. Please enter the 6 digits OTP value to complete the verification.' : $this->config->get('fraud_fraudlabsprosmsverification_msg_otp_success');
		$data['flp_sms_msg_otp_fail'] = ($this->config->get('fraud_fraudlabsprosmsverification_msg_otp_fail') == '') ? 'Error: Unable to send the SMS verification message to {phone}.' : $this->config->get('fraud_fraudlabsprosmsverification_msg_otp_fail');
		$data['flp_sms_msg_invalid_phone'] = ($this->config->get('fraud_fraudlabsprosmsverification_msg_invalid_phone') == '') ? 'Please enter a valid phone number.' : $this->config->get('fraud_fraudlabsprosmsverification_msg_invalid_phone');
		$data['flp_sms_msg_invalid_otp'] = ($this->config->get('fraud_fraudlabsprosmsverification_msg_invalid_otp') == '') ? 'Error: Invalid OTP. Please enter the correct OTP.' : $this->config->get('fraud_fraudlabsprosmsverification_msg_invalid_otp');

		// Load the template file and show output
		$this->response->setOutput($this->load->view('extension/fraudlabsprosmsverification/fraud/fraudlabsprosmsverification', $data));
		return 0;
	}

	public function before_checkout_success(): int {
		// Do not perform fraud SMS verification if FraudLabs Pro SMS Verification is disabled.
		if (!$this->config->get('fraud_fraudlabsprosmsverification_status')) {
			$this->write_debug_log('FraudLabs Pro SMS Verification is disabled. SMS Verification will not be performed.');
			return 0;
		}

		if (isset($this->session->data['order_id'])) {
			$orderId = $this->session->data['order_id'];

			$query = $this->db->query("SELECT `fraudlabspro_status`, `fraudlabspro_id` FROM `" . DB_PREFIX . "fraudlabspro` WHERE order_id = '" . $orderId . "';");
			$flpStatus = $query->row['fraudlabspro_status'];
			$flpId = $query->row['fraudlabspro_id'];

			$query = $this->db->query("SELECT `email` FROM `" . DB_PREFIX . "order` WHERE order_id = '" . $orderId . "';");
			$emailAddress = $query->row['email'];

			if ($emailAddress == '') {
				$this->write_debug_log('Billing email not found. Email Verification will not be performed for Order ID ' . $orderId . '.');
				return 0;
			}

			if ($flpStatus == 'REVIEW') {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fraudlabsprosmsverification` WHERE order_id = '" . $orderId . "';");
				// Do not send email if sms data existed.
				if ($query->num_rows) {
					return 0;
				}

				if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
					$code = $this->randomCode(20);
					$link = $_SERVER["SERVER_NAME"] . '/index.php?route=extension/fraudlabsprosmsverification/fraud/fraudlabsprosmsverification&orderid=' . $orderId . '&code=' . $code;
					$this->db->query("INSERT INTO `" . DB_PREFIX . "fraudlabsprosmsverification` VALUES ('" . $orderId . "', '" . $code . "', '', '', '" . $flpId . "', '" . $this->config->get('fraud_fraudlabsprosmsverification_key') . "');");

					$emailTitle = (null !== $this->config->get('fraud_fraudlabsprosmsverification_sms_email_subject')) ? (($this->config->get('fraud_fraudlabsprosmsverification_sms_email_subject') == '') ? "Action Required: SMS Verification is required to process the order." : $this->config->get('fraud_fraudlabsprosmsverification_sms_email_subject')) : "Action Required: SMS Verification is required to process the order.";
					$emailContent = (null !== $this->config->get('fraud_fraudlabsprosmsverification_sms_email_content')) ? (($this->config->get('fraud_fraudlabsprosmsverification_sms_email_content') == '') ? "Dear Customer,\nThanks for your business. Before we can process your order, you are required to complete the SMS verification by clicking the below link:\n{email_verification_link}\nThank you." : $this->config->get('fraud_fraudlabsprosmsverification_sms_email_content')) : "Dear Customer,\nThanks for your business. Before we can process your order, you are required to complete the SMS verification by clicking the below link:\n{email_verification_link}\nThank you.";

					if (strpos($emailContent, '{email_verification_link}') !== false) {
						$message = str_replace('{email_verification_link}', $link, $emailContent);
					}
					$this->write_debug_log('Email is ready to send for Order ID ' . $orderId . ' with code: ' . $code . '.');

					$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'));
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			
					$mail->setTo($emailAddress);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
					$mail->setSubject(html_entity_decode($emailTitle, ENT_QUOTES, 'UTF-8'));
					$mail->setText($message);
					$emailResult = $mail->send();
					$emailResult = (string)$emailResult;

					if (strpos($emailResult, 'Error') !== false) {
						$this->write_debug_log('Email sent successfully for Order ID ' . $orderId . ' with code: ' . $code . '.');
					} else {
						$this->write_debug_log('Email sent fail for Order ID ' . $orderId . ' with code: ' . $code . '.');
					}
					return 0;
				} else {
					$this->write_debug_log('Billing email is not a valid email. Email Verification will not be performed for Order ID ' . $orderId . '.');
					return 0;
				}
			}
			return 0;
		}
		return 0;
	}

	public function sms_send(): void {
		$apiKey = $this->config->get('fraud_fraudlabsprosmsverification_key');
		$smsOrderId = (isset($this->request->post['sms_order_id'])) ? $this->request->post['sms_order_id'] : "";
		$tel = (isset($this->request->post['tel'])) ? $this->request->post['tel'] : die('ERROR 400-2');
		if ($smsOrderId != "") {
			$query = $this->db->query("SELECT `fraudlabspro_id` FROM `" . DB_PREFIX . "fraudlabsprosmsverification` WHERE order_id = '" . $smsOrderId . "';");
			$flpId = $query->row['fraudlabspro_id'];
		} else {
			$flpId = '';
		}

		$params['format'] = 'json';
		$params['tel'] = trim($tel);
		if (strpos($params['tel'], '+') !== 0)
			$params['tel'] = '+' . $params['tel'];
		$params['mesg'] = $this->config->get('fraud_fraudlabsprosmsverification_sms_template');
		$params['mesg'] = str_replace(['{', '}'], ['<', '>'], $params['mesg']);
		$params['flp_id'] = $flpId;
		$params['tel_cc'] = (isset($this->request->post['tel_cc'])) ? $this->request->post['tel_cc'] : "";
		$params['otp_timeout'] = $this->config->get('fraud_fraudlabsprosmsverification_sms_otp_timeout');
		$params['source'] = 'opencart';
		$url = 'https://api.fraudlabspro.com/v1/verification/send';

		$query = '';

		foreach($params as $key=>$value) {
			$query .= '&' . $key . '=' . rawurlencode($value);
		}

		$url = $url . '?key=' . $apiKey . $query;

		$result = file_get_contents($url);

		// Network error, wait 2 seconds for next retry
		if (!$result) {
			for ($i = 0; $i < 3; ++$i) {
				sleep(2);
				$result = file_get_contents($url);
			}
		}

		// Still having network issue after 3 retries, give up
		if (!$result)
			die ('ERROR 500');

		// Get the HTTP response
		$data = json_decode($result);

		if (trim($data->error) != '') {
			die('ERROR 600-' . $data->error);
		} else {
			die ('OK' . $data->tran_id . $data->otp_char);
		}
	}

	public function sms_verify(): void {
		$apiKey = $this->config->get('fraud_fraudlabsprosmsverification_key');
		$params['format'] = 'json';
		$params['otp'] = (isset($this->request->post['otp'])) ? $this->request->post['otp'] : die('ERROR 400-3');
		$params['tran_id'] = (isset($this->request->post['tran_id'])) ? $this->request->post['tran_id'] : die('ERROR 400-4');
		$url = 'https://api.fraudlabspro.com/v1/verification/result';

		$query = '';

		foreach($params as $key=>$value) {
			$query .= '&' . $key . '=' . rawurlencode($value);
		}

		$url = $url . '?key=' . $apiKey . $query;

		$result = file_get_contents($url);

		// Network error, wait 2 seconds for next retry
		if (!$result) {
			for ($i = 0; $i < 3; ++$i) {
				sleep(2);
				$result = file_get_contents($url);
			}
		}

		// Still having network issue after 3 retries, give up
		if (!$result)
			die ('ERROR 500');

		// Get the HTTP response
		$data = json_decode($result);

		if (trim($data->error) != '') {
			if ($data->error == 'Invalid OTP.') {
				die('ERROR 601-' . $data->error);
			} else {
				die('ERROR 600-' . $data->error);
			}
		} else {
			$smsOrderId = (isset($this->request->post['sms_order_id'])) ? $this->request->post['sms_order_id'] : "";
			$smsCode = (isset($this->request->post['sms_code'])) ? $this->request->post['sms_code'] : "";
			$smsPhone = (isset($this->request->post['sms_tel'])) ? $this->request->post['sms_tel'] : "";
			if (($smsOrderId != "") && ($smsCode != "")) {
				$query = $this->db->query("SELECT `fraudlabspro_sms_email_code` FROM `" . DB_PREFIX . "fraudlabsprosmsverification` WHERE order_id = '" . $smsOrderId . "' AND `fraudlabspro_sms_email_code` = '" . $smsCode . "';");
				$emailCode = $query->row['fraudlabspro_sms_email_code'];

				if ($emailCode == $smsCode) {
					$this->db->query("UPDATE `" . DB_PREFIX . "fraudlabsprosmsverification` SET `fraudlabspro_sms_email_code` = '" . $smsCode . "_VERIFIED', `fraudlabspro_sms_email_phone` = '" . $smsPhone . "', `fraudlabspro_sms_email_sms` = 'VERIFIED' WHERE `order_id` = '" . $smsOrderId . "' AND `fraudlabspro_sms_email_code` = '" . $smsCode . "';");

					$query = $this->db->query("SELECT `is_phone_verified` FROM `" . DB_PREFIX . "fraudlabspro` WHERE order_id = '" . $smsOrderId . "';");
					$isPhoneVerified = $query->row['is_phone_verified'];

					if ($isPhoneVerified == '') {
						$this->db->query("UPDATE `" . DB_PREFIX . "fraudlabspro` SET `is_phone_verified` = '" . $smsPhone . " verified' WHERE `order_id` = '" . $smsOrderId . "';");
					}
				}
			}
			die ('OK');
		}
	}

	// Generate random code for email verification.
	private function randomCode(int $length=16): string {
		$key = '';
		$pattern = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		for($i=0; $i<$length; $i++) {
			$key .= $pattern[rand(0, strlen($pattern)-1)];
		}
		return $key;
	}

	// Write to debug log to record details of process.
	private function write_debug_log(string $message): int {
		if (!$this->config->get('fraud_fraudlabsprosmsverification_debug_status')) {
			return 0;
		}
		$log = new \Opencart\System\Library\Log('FLP_SMS_debug.log');
		$log->write($message);
		return 0;
	}
}