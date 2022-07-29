<?php
namespace Opencart\Admin\Model\Extension\Fraudlabsprosmsverification\Fraud;
class Fraudlabsprosmsverification extends \Opencart\System\Engine\Model {
	public function install(): void {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fraudlabsprosmsverification` (
				`order_id` VARCHAR(11) NOT NULL,
				`fraudlabspro_sms_email_code` VARCHAR(30) NOT NULL,
				`fraudlabspro_sms_email_phone` VARCHAR(30) NULL,
				`fraudlabspro_sms_email_sms` VARCHAR(20) NOT NULL,
				`fraudlabspro_id` VARCHAR(15) NOT NULL,
				`api_key` CHAR(32) NOT NULL,
				PRIMARY KEY (`order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`code`, `key`, `value`, `serialized`) VALUES ('fraud_fraudlabsprosmsverification', 'fraud_fraudlabsprosmsverification_sms_template', 'Hi, your OTP is {otp}.', '0');");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`code`, `key`, `value`, `serialized`) VALUES ('fraud_fraudlabsprosmsverification', 'fraud_fraudlabsprosmsverification_sms_otp_timeout', '3600', '0');");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`code`, `key`, `value`, `serialized`) VALUES ('fraud_fraudlabsprosmsverification', 'fraud_fraudlabsprosmsverification_sms_tel_cc', 'US', '0');");
	}

	public function uninstall(): void {
		// $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "fraudlabsprosmsverification`");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = 'fraud_fraudlabsprosmsverification'");
	}

	public function getOrder(int $order_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fraudlabsprosmsverification` WHERE order_id = '" . (int)$order_id . "'");

		return $query->row;
	}
}