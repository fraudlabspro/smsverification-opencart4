<?php
namespace Opencart\Admin\Controller\Extension\Fraudlabsprosmsverification\Fraud;
class Fraudlabsprosmsverification extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/fraudlabsprosmsverification/fraud/fraudlabsprosmsverification');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=fraud')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/fraudlabsprosmsverification/fraud/fraudlabsprosmsverification', 'user_token=' . $this->session->data['user_token'])
		];

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('fraud_fraudlabsprosmsverification', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=fraud'));
		}

		$data['save'] = $this->url->link('extension/fraudlabsprosmsverification/fraud/fraudlabsprosmsverification|save', 'user_token=' . $this->session->data['user_token']);

		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=fraud');

		$countries = ['AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia, Plurinational State of', 'BQ' => 'Bonaire, Sint Eustatius and Saba', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'BN' => 'Brunei Darussalam', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'CV' => 'Cabo Verde', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos (Keeling) Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CG' => 'Congo', 'CD' => 'Congo, The Democratic Republic of The', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'CI' => 'Cote D\'ivoire', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CW' => 'Curacao', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands (Malvinas)', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island and Mcdonald Islands', 'VA' => 'Holy See', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran, Islamic Republic of', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'KP' => 'Korea, Democratic People\'s Republic of', 'KR' => 'Korea, Republic of', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Lao People\'s Democratic Republic', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia, The Former Yugoslav Republic of', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia, Federated States of', 'MD' => 'Moldova, Republic of', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestine, State of', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russian Federation', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena, Ascension and Tristan Da Cunha', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin (French Part)', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent and The Grenadines', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SX' => 'Sint Maarten (Dutch Part)', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia and The South Sandwich Islands', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Eswatini', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syrian Arab Republic', 'TW' => 'Taiwan, Province of China', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania, United Republic of', 'TH' => 'Thailand', 'TL' => 'Timor-Leste', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UM' => 'United States Minor Outlying Islands', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VE' => 'Venezuela, Bolivarian Republic of', 'VN' => 'Viet Nam', 'VG' => 'Virgin Islands, British', 'VI' => 'Virgin Islands, U.S.', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe'];

		if (isset($this->request->post['fraud_fraudlabsprosmsverification_status'])) {
			$data['fraud_fraudlabsprosmsverification_status'] = $this->request->post['fraud_fraudlabsprosmsverification_status'];
		} else {
			$data['fraud_fraudlabsprosmsverification_status'] = $this->config->get('fraud_fraudlabsprosmsverification_status');
		}

		if (isset($this->request->post['fraud_fraudlabsprosmsverification_key'])) {
			$data['fraud_fraudlabsprosmsverification_key'] = $this->request->post['fraud_fraudlabsprosmsverification_key'];
		} else {
			$data['fraud_fraudlabsprosmsverification_key'] = $this->config->get('fraud_fraudlabsprosmsverification_key');
		}

		if (isset($this->request->post['fraud_fraudlabsprosmsverification_sms_instruction'])) {
			$data['fraud_fraudlabsprosmsverification_sms_instruction'] = $this->request->post['fraud_fraudlabsprosmsverification_sms_instruction'];
		} else {
			$data['fraud_fraudlabsprosmsverification_sms_instruction'] = $this->config->get('fraud_fraudlabsprosmsverification_sms_instruction');
		}

		if (isset($this->request->post['fraud_fraudlabsprosmsverification_sms_template'])) {
			$data['fraud_fraudlabsprosmsverification_sms_template'] = $this->request->post['fraud_fraudlabsprosmsverification_sms_template'];
		} else {
			$data['fraud_fraudlabsprosmsverification_sms_template'] = $this->config->get('fraud_fraudlabsprosmsverification_sms_template');
		}

		if (isset($this->request->post['fraud_fraudlabsprosmsverification_sms_email_subject'])) {
			$data['fraud_fraudlabsprosmsverification_sms_email_subject'] = $this->request->post['fraud_fraudlabsprosmsverification_sms_email_subject'];
		} else {
			$data['fraud_fraudlabsprosmsverification_sms_email_subject'] = $this->config->get('fraud_fraudlabsprosmsverification_sms_email_subject');
		}

		if (isset($this->request->post['fraud_fraudlabsprosmsverification_sms_email_content'])) {
			$data['fraud_fraudlabsprosmsverification_sms_email_content'] = $this->request->post['fraud_fraudlabsprosmsverification_sms_email_content'];
		} else {
			$data['fraud_fraudlabsprosmsverification_sms_email_content'] = $this->config->get('fraud_fraudlabsprosmsverification_sms_email_content');
		}

		if (isset($this->request->post['fraud_fraudlabsprosmsverification_sms_otp_timeout'])) {
			$data['fraud_fraudlabsprosmsverification_sms_otp_timeout'] = $this->request->post['fraud_fraudlabsprosmsverification_sms_otp_timeout'];
		} else {
			$data['fraud_fraudlabsprosmsverification_sms_otp_timeout'] = $this->config->get('fraud_fraudlabsprosmsverification_sms_otp_timeout');
		}

		if (isset($this->request->post['fraud_fraudlabsprosmsverification_sms_tel_cc'])) {
			$data['fraud_fraudlabsprosmsverification_sms_tel_cc'] = $this->request->post['fraud_fraudlabsprosmsverification_sms_tel_cc'];
		} else {
			$data['fraud_fraudlabsprosmsverification_sms_tel_cc'] = $this->config->get('fraud_fraudlabsprosmsverification_sms_tel_cc');
		}

		$data['fraud_fraudlabsprosmsverification_countries'] = '';
		foreach ($countries as $country_code => $country_name) {
			$data['fraud_fraudlabsprosmsverification_countries'] .= '<option value="' . $country_code . '"' . (($data['fraud_fraudlabsprosmsverification_sms_tel_cc'] == $country_code ) ? ' selected' : '') . '> ' . $country_name . '</option>';
		}

		if (isset($this->request->post['fraud_fraudlabsprosmsverification_msg_otp_success'])) {
			$data['fraud_fraudlabsprosmsverification_msg_otp_success'] = $this->request->post['fraud_fraudlabsprosmsverification_msg_otp_success'];
		} else {
			$data['fraud_fraudlabsprosmsverification_msg_otp_success'] = $this->config->get('fraud_fraudlabsprosmsverification_msg_otp_success');
		}

		if (isset($this->request->post['fraud_fraudlabsprosmsverification_msg_otp_fail'])) {
			$data['fraud_fraudlabsprosmsverification_msg_otp_fail'] = $this->request->post['fraud_fraudlabsprosmsverification_msg_otp_fail'];
		} else {
			$data['fraud_fraudlabsprosmsverification_msg_otp_fail'] = $this->config->get('fraud_fraudlabsprosmsverification_msg_otp_fail');
		}

		if (isset($this->request->post['fraud_fraudlabsprosmsverification_msg_invalid_phone'])) {
			$data['fraud_fraudlabsprosmsverification_msg_invalid_phone'] = $this->request->post['fraud_fraudlabsprosmsverification_msg_invalid_phone'];
		} else {
			$data['fraud_fraudlabsprosmsverification_msg_invalid_phone'] = $this->config->get('fraud_fraudlabsprosmsverification_msg_invalid_phone');
		}

		if (isset($this->request->post['fraud_fraudlabsprosmsverification_msg_invalid_otp'])) {
			$data['fraud_fraudlabsprosmsverification_msg_invalid_otp'] = $this->request->post['fraud_fraudlabsprosmsverification_msg_invalid_otp'];
		} else {
			$data['fraud_fraudlabsprosmsverification_msg_invalid_otp'] = $this->config->get('fraud_fraudlabsprosmsverification_msg_invalid_otp');
		}

		if (isset($this->request->post['fraud_fraudlabsprosmsverification_debug_status'])) {
			$data['fraud_fraudlabsprosmsverification_debug_status'] = $this->request->post['fraud_fraudlabsprosmsverification_debug_status'];
		} else {
			$data['fraud_fraudlabsprosmsverification_debug_status'] = $this->config->get('fraud_fraudlabsprosmsverification_debug_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/fraudlabsprosmsverification/fraud/fraudlabsprosmsverification', $data));
	}

	public function save(): void {
		$this->load->language('extension/fraudlabsprosmsverification/fraud/fraudlabsprosmsverification');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/fraudlabsprosmsverification/fraud/fraudlabsprosmsverification')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['fraud_fraudlabsprosmsverification_key']) {
			$json['error'] = $this->language->get('error_key');
		}

		if (strpos($this->request->post['fraud_fraudlabsprosmsverification_sms_template'], '{otp}') == false) {
			$json['error'] = $this->language->get('error_sms_template');
		}

		if ($this->request->post['fraud_fraudlabsprosmsverification_sms_email_content'] != '') {
			if (strpos($this->request->post['fraud_fraudlabsprosmsverification_sms_email_content'], '{email_verification_link}') == false) {
				$json['error'] = $this->language->get('error_sms_email_content');
			}
		}

		if ($this->request->post['fraud_fraudlabsprosmsverification_msg_otp_success'] != '') {
			if (strpos($this->request->post['fraud_fraudlabsprosmsverification_msg_otp_success'], '{phone}') == false) {
				$json['error'] = $this->language->get('error_msg_otp_success');
			}
		}

		if ($this->request->post['fraud_fraudlabsprosmsverification_msg_otp_fail'] != '') {
			if (strpos($this->request->post['fraud_fraudlabsprosmsverification_msg_otp_fail'], '{phone}') == false) {
				$json['error'] = $this->language->get('error_msg_otp_fail');
			}
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('fraud_fraudlabsprosmsverification', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install(): void {
		$this->load->model('extension/fraudlabsprosmsverification/fraud/fraudlabsprosmsverification');
		$this->model_extension_fraudlabsprosmsverification_fraud_fraudlabsprosmsverification->install();

		$this->load->model('setting/event');
		$this->model_setting_event->addEvent('fraudlabsprosmsverification', '', 'catalog/controller/checkout/success/before', 'extension/fraudlabsprosmsverification/fraud/fraudlabsprosmsverification|before_checkout_success');
	}

	public function uninstall(): void {
		$this->load->model('extension/fraudlabsprosmsverification/fraud/fraudlabsprosmsverification');
		$this->model_extension_fraudlabsprosmsverification_fraud_fraudlabsprosmsverification->uninstall();

		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode('fraudlabsprosmsverification');
	}
}