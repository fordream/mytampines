<?php
/*------------------------------------------------------------------------
# En Masse - Social Buying Extension 2010
# ------------------------------------------------------------------------
# By Matamko.com
# Copyright (C) 2010 Matamko.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.matamko.com
# Technical Support:  Visit our forum at www.matamko.com
-------------------------------------------------------------------------*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport( 'joomla.application.component.model' );

class EnmasseModelSetting extends JModel
{
	function getSetting($id)
	{
		$row =& JTable::getInstance('setting', 'Table');
		$row->load($id);

		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $row;
	}
	
	function getCurrencyPrefix()
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT currency_prefix FROM #__enmasse_setting WHERE id = 1';
		$db->setQuery($query);
		$prefix = $db->loadResult();;
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $prefix ;
	}
	
	function getCurrencyPostfix()
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT currency_postfix FROM #__enmasse_setting WHERE id = 1';
		$db->setQuery($query);
		$postfix = $db->loadResult();;
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $postfix ;
	}

	function store($data)
	{
		$row =& $this->getTable();

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if($row->id <= 0)
			$row ->created_at = DatetimeWrapper::getDatetimeOfNow();
		$row ->updated_at = DatetimeWrapper::getDatetimeOfNow();

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}
	
	function listCountryJOpt( $name = '', $selected = '',$required=false )
	{
		$countries = array();

		$countries[] = JHTML::_('select.option',  '', '- '. JText::_( 'SELECT_COUNTRY' ) .' -' );
		$countries[] = JHTML::_('select.option',  'US', 'United States' );
		$countries[] = JHTML::_('select.option',  'CA', 'Canada' );
		$countries[] = JHTML::_('select.option',  '', '----------' );
		$countries[] = JHTML::_('select.option',  'AF', 'Afghanistan');
		$countries[] = JHTML::_('select.option',  'AL', 'Albania');
		$countries[] = JHTML::_('select.option',  'DZ', 'Algeria');
		$countries[] = JHTML::_('select.option',  'AS', 'American Samoa');
		$countries[] = JHTML::_('select.option',  'AD', 'Andorra');
		$countries[] = JHTML::_('select.option',  'AO', 'Angola');
		$countries[] = JHTML::_('select.option',  'AI', 'Anguilla');
		$countries[] = JHTML::_('select.option',  'AQ', 'Antarctica');
		$countries[] = JHTML::_('select.option',  'AG', 'Antigua and Barbuda');
		$countries[] = JHTML::_('select.option',  'AR', 'Argentina');
		$countries[] = JHTML::_('select.option',  'AM', 'Armenia');
		$countries[] = JHTML::_('select.option',  'AW', 'Aruba');
		$countries[] = JHTML::_('select.option',  'AU', 'Australia');
		$countries[] = JHTML::_('select.option',  'AT', 'Austria');
		$countries[] = JHTML::_('select.option',  'AZ', 'Azerbaijan');
		$countries[] = JHTML::_('select.option',  'BS', 'Bahamas');
		$countries[] = JHTML::_('select.option',  'BH', 'Bahrain');
		$countries[] = JHTML::_('select.option',  'BD', 'Bangladesh');
		$countries[] = JHTML::_('select.option',  'BB', 'Barbados');
		$countries[] = JHTML::_('select.option',  'BY', 'Belarus');
		$countries[] = JHTML::_('select.option',  'BE', 'Belgium');
		$countries[] = JHTML::_('select.option',  'BZ', 'Belize');
		$countries[] = JHTML::_('select.option',  'BJ', 'Benin');
		$countries[] = JHTML::_('select.option',  'BM', 'Bermuda');
		$countries[] = JHTML::_('select.option',  'BT', 'Bhutan');
		$countries[] = JHTML::_('select.option',  'BO', 'Bolivia');
		$countries[] = JHTML::_('select.option',  'BA', 'Bosnia and Herzegovina');
		$countries[] = JHTML::_('select.option',  'BW', 'Botswana');
		$countries[] = JHTML::_('select.option',  'BV', 'Bouvet Island');
		$countries[] = JHTML::_('select.option',  'BR', 'Brazil');
		$countries[] = JHTML::_('select.option',  'IO', 'British Indian Ocean Territory');
		$countries[] = JHTML::_('select.option',  'BN', 'Brunei Darussalam');
		$countries[] = JHTML::_('select.option',  'BG', 'Bulgaria');
		$countries[] = JHTML::_('select.option',  'BF', 'Burkina Faso');
		$countries[] = JHTML::_('select.option',  'BI', 'Burundi');
		$countries[] = JHTML::_('select.option',  'KH', 'Cambodia');
		$countries[] = JHTML::_('select.option',  'CM', 'Cameroon');
		$countries[] = JHTML::_('select.option',  'CV', 'Cape Verde');
		$countries[] = JHTML::_('select.option',  'KY', 'Cayman Islands');
		$countries[] = JHTML::_('select.option',  'CF', 'Central African Republic');
		$countries[] = JHTML::_('select.option',  'TD', 'Chad');
		$countries[] = JHTML::_('select.option',  'CL', 'Chile');
		$countries[] = JHTML::_('select.option',  'CN', 'China');
		$countries[] = JHTML::_('select.option',  'CX', 'Christmas Island');
		$countries[] = JHTML::_('select.option',  'CC', 'Cocos (Keeling) Islands');
		$countries[] = JHTML::_('select.option',  'CO', 'Colombia');
		$countries[] = JHTML::_('select.option',  'KM', 'Comoros');
		$countries[] = JHTML::_('select.option',  'CG', 'Congo');
		$countries[] = JHTML::_('select.option',  'CD', 'Congo, the Democratic Republic of The');
		$countries[] = JHTML::_('select.option',  'CK', 'Cook Islands');
		$countries[] = JHTML::_('select.option',  'CR', 'Costa Rica');
		$countries[] = JHTML::_('select.option',  'CI', 'Cote D\'ivoire');
		$countries[] = JHTML::_('select.option',  'HR', 'Croatia');
		$countries[] = JHTML::_('select.option',  'CU', 'Cuba');
		$countries[] = JHTML::_('select.option',  'CY', 'Cyprus');
		$countries[] = JHTML::_('select.option',  'CZ', 'Czech Republic');
		$countries[] = JHTML::_('select.option',  'DK', 'Denmark');
		$countries[] = JHTML::_('select.option',  'DJ', 'Djibouti');
		$countries[] = JHTML::_('select.option',  'DM', 'Dominica');
		$countries[] = JHTML::_('select.option',  'DO', 'Dominican Republic');
		$countries[] = JHTML::_('select.option',  'EC', 'Ecuador');
		$countries[] = JHTML::_('select.option',  'EG', 'Egypt');
		$countries[] = JHTML::_('select.option',  'sV', 'El Salvador');
		$countries[] = JHTML::_('select.option',  'GQ', 'Equatorial Guinea');
		$countries[] = JHTML::_('select.option',  'ER', 'Eritrea');
		$countries[] = JHTML::_('select.option',  'EE', 'Estonia');
		$countries[] = JHTML::_('select.option',  'ET', 'Ethiopia');
		$countries[] = JHTML::_('select.option',  'FK', 'Falkland Islands (Malvinas)');
		$countries[] = JHTML::_('select.option',  'FO', 'Faroe Islands');
		$countries[] = JHTML::_('select.option',  'FJ', 'Fiji');
		$countries[] = JHTML::_('select.option',  'FI', 'Finland');
		$countries[] = JHTML::_('select.option',  'FR', 'France');
		$countries[] = JHTML::_('select.option',  'GF', 'French Guiana');
		$countries[] = JHTML::_('select.option',  'PF', 'French Polynesia');
		$countries[] = JHTML::_('select.option',  'TF', 'French Southern Territories');
		$countries[] = JHTML::_('select.option',  'GA', 'Gabon');
		$countries[] = JHTML::_('select.option',  'GM', 'Gambia');
		$countries[] = JHTML::_('select.option',  'GE', 'Georgia');
		$countries[] = JHTML::_('select.option',  'DE', 'Germany');
		$countries[] = JHTML::_('select.option',  'GH', 'Ghana');
		$countries[] = JHTML::_('select.option',  'GI', 'Gibraltar');
		$countries[] = JHTML::_('select.option',  'GR', 'Greece');
		$countries[] = JHTML::_('select.option',  'GL', 'Greenland');
		$countries[] = JHTML::_('select.option',  'GD', 'Grenada');
		$countries[] = JHTML::_('select.option',  'GP', 'Guadeloupe');
		$countries[] = JHTML::_('select.option',  'GU', 'Guam');
		$countries[] = JHTML::_('select.option',  'GT', 'Guatemala');
		$countries[] = JHTML::_('select.option',  'GG', 'Guernsey');
		$countries[] = JHTML::_('select.option',  'GN', 'Guinea');
		$countries[] = JHTML::_('select.option',  'GW', 'Guinea-Bissau');
		$countries[] = JHTML::_('select.option',  'GY', 'Guyana');
		$countries[] = JHTML::_('select.option',  'HT', 'Haiti');
		$countries[] = JHTML::_('select.option',  'HM', 'Heard Island and Mcdonald Islands');
		$countries[] = JHTML::_('select.option',  'HN', 'Honduras');
		$countries[] = JHTML::_('select.option',  'HK', 'Hong Kong');
		$countries[] = JHTML::_('select.option',  'HU', 'Hungary');
		$countries[] = JHTML::_('select.option',  'IS', 'Iceland');
		$countries[] = JHTML::_('select.option',  'IN', 'India');
		$countries[] = JHTML::_('select.option',  'ID', 'Indonesia');
		$countries[] = JHTML::_('select.option',  'IR', 'Iran, Islamic Republic Of');
		$countries[] = JHTML::_('select.option',  'IQ', 'Iraq');
		$countries[] = JHTML::_('select.option',  'IE', 'Ireland');
		$countries[] = JHTML::_('select.option',  'IM', 'Isle of Man');
		$countries[] = JHTML::_('select.option',  'IL', 'Israel');
		$countries[] = JHTML::_('select.option',  'IT', 'Italy');
		$countries[] = JHTML::_('select.option',  'JM', 'Jamaica');
		$countries[] = JHTML::_('select.option',  'JP', 'Japan');
		$countries[] = JHTML::_('select.option',  'JE', 'Jersey');
		$countries[] = JHTML::_('select.option',  'JO', 'Jordan');
		$countries[] = JHTML::_('select.option',  'KZ', 'Kazakhstan');
		$countries[] = JHTML::_('select.option',  'KE', 'Kenya');
		$countries[] = JHTML::_('select.option',  'KI', 'Kiribati');
		$countries[] = JHTML::_('select.option',  'KP', 'Korea, Democratic People\'s Republic Of');
		$countries[] = JHTML::_('select.option',  'KR', 'Korea, Republic Of');
		$countries[] = JHTML::_('select.option',  'KW', 'Kuwait');
		$countries[] = JHTML::_('select.option',  'KG', 'Kyrgyzstan');
		$countries[] = JHTML::_('select.option',  'LA', 'Lao People\'s Democratic Republic');
		$countries[] = JHTML::_('select.option',  'LV', 'Latvia');
		$countries[] = JHTML::_('select.option',  'LB', 'Lebanon');
		$countries[] = JHTML::_('select.option',  'LS', 'Lesotho');
		$countries[] = JHTML::_('select.option',  'LR', 'Liberia');
		$countries[] = JHTML::_('select.option',  'LY', 'Libyan Arab Jamahiriya');
		$countries[] = JHTML::_('select.option',  'LI', 'Liechtenstein');
		$countries[] = JHTML::_('select.option',  'LT', 'Lithuania');
		$countries[] = JHTML::_('select.option',  'LU', 'Luxembourg');
		$countries[] = JHTML::_('select.option',  'MO', 'Macao');
		$countries[] = JHTML::_('select.option',  'MK', 'Macedonia, the Former Yugoslav Republic Of');
		$countries[] = JHTML::_('select.option',  'MG', 'Madagascar');
		$countries[] = JHTML::_('select.option',  'MW', 'Malawi');
		$countries[] = JHTML::_('select.option',  'MY', 'Malaysia');
		$countries[] = JHTML::_('select.option',  'MV', 'Maldives');
		$countries[] = JHTML::_('select.option',  'ML', 'Mali');
		$countries[] = JHTML::_('select.option',  'MT', 'Malta');
		$countries[] = JHTML::_('select.option',  'MH', 'Marshall Islands');
		$countries[] = JHTML::_('select.option',  'MQ', 'Martinique');
		$countries[] = JHTML::_('select.option',  'MR', 'Mauritania');
		$countries[] = JHTML::_('select.option',  'MU', 'Mauritius');
		$countries[] = JHTML::_('select.option',  'YT', 'Mayotte');
		$countries[] = JHTML::_('select.option',  'MX', 'Mexico');
		$countries[] = JHTML::_('select.option',  'FM', 'Micronesia, Federated States Of');
		$countries[] = JHTML::_('select.option',  'MD', 'Moldova');
		$countries[] = JHTML::_('select.option',  'MC', 'Monaco');
		$countries[] = JHTML::_('select.option',  'MN', 'Mongolia');
		$countries[] = JHTML::_('select.option',  'ME', 'Montenegro');
		$countries[] = JHTML::_('select.option',  'MS', 'Montserrat');
		$countries[] = JHTML::_('select.option',  'MA', 'Morocco');
		$countries[] = JHTML::_('select.option',  'MZ', 'Mozambique');
		$countries[] = JHTML::_('select.option',  'MM', 'Myanmar');
		$countries[] = JHTML::_('select.option',  'NA', 'Namibia');
		$countries[] = JHTML::_('select.option',  'NR', 'Nauru');
		$countries[] = JHTML::_('select.option',  'NP', 'Nepal');
		$countries[] = JHTML::_('select.option',  'NL', 'Netherlands');
		$countries[] = JHTML::_('select.option',  'AN', 'Netherlands Antilles');
		$countries[] = JHTML::_('select.option',  'NC', 'New Caledonia');
		$countries[] = JHTML::_('select.option',  'NZ', 'New Zealand');
		$countries[] = JHTML::_('select.option',  'NI', 'Nicaragua');
		$countries[] = JHTML::_('select.option',  'NE', 'Niger');
		$countries[] = JHTML::_('select.option',  'NG', 'Nigeria');
		$countries[] = JHTML::_('select.option',  'NU', 'Niue');
		$countries[] = JHTML::_('select.option',  'NF', 'Norfolk Island');
		$countries[] = JHTML::_('select.option',  'MP', 'Northern Mariana Islands');
		$countries[] = JHTML::_('select.option',  'NO', 'Norway');
		$countries[] = JHTML::_('select.option',  'OM', 'Oman');
		$countries[] = JHTML::_('select.option',  'PK', 'Pakistan');
		$countries[] = JHTML::_('select.option',  'PW', 'Palau');
		$countries[] = JHTML::_('select.option',  'PS', 'Palestinian Territory, Occupied');
		$countries[] = JHTML::_('select.option',  'PA', 'Panama');
		$countries[] = JHTML::_('select.option',  'PG', 'Papua New Guinea');
		$countries[] = JHTML::_('select.option',  'PY', 'Paraguay');
		$countries[] = JHTML::_('select.option',  'PE', 'Peru');
		$countries[] = JHTML::_('select.option',  'PH', 'Philippines');
		$countries[] = JHTML::_('select.option',  'PN', 'Pitcairn');
		$countries[] = JHTML::_('select.option',  'PL', 'Poland');
		$countries[] = JHTML::_('select.option',  'PT', 'Portugal');
		$countries[] = JHTML::_('select.option',  'PR', 'Puerto Rico');
		$countries[] = JHTML::_('select.option',  'QA', 'Qatar');
		$countries[] = JHTML::_('select.option',  'RE', 'Reunion');
		$countries[] = JHTML::_('select.option',  'RO', 'Romania');
		$countries[] = JHTML::_('select.option',  'RU', 'Russian Federation');
		$countries[] = JHTML::_('select.option',  'RW', 'Rwanda');
		$countries[] = JHTML::_('Select.option',  'BL', 'Saint Bartelemy');
		$countries[] = JHTML::_('Select.option',  'SH', 'Saint Helena');
		$countries[] = JHTML::_('Select.option',  'KN', 'Saint Kitts and Nevis');
		$countries[] = JHTML::_('Select.option',  'LC', 'Saint Lucia');
		$countries[] = JHTML::_('Select.option',  'MF', 'Saint Martin');
		$countries[] = JHTML::_('Select.option',  'PM', 'Saint Pierre and Miquelon');
		$countries[] = JHTML::_('Select.option',  'VC', 'Saint Vincent and the Grenadines');
		$countries[] = JHTML::_('Select.option',  'WS', 'Samoa');
		$countries[] = JHTML::_('Select.option',  'SM', 'San Marino');
		$countries[] = JHTML::_('Select.option',  'ST', 'Sao Tome and Principe');
		$countries[] = JHTML::_('Select.option',  'SA', 'Saudi Arabia');
		$countries[] = JHTML::_('Select.option',  'SN', 'Senegal');
		$countries[] = JHTML::_('Select.option',  'RS', 'Serbia');
		$countries[] = JHTML::_('Select.option',  'SC', 'Seychelles');
		$countries[] = JHTML::_('Select.option',  'SL', 'Sierra Leone');
		$countries[] = JHTML::_('Select.option',  'SG', 'Singapore');
		$countries[] = JHTML::_('Select.option',  'SK', 'Slovakia');
		$countries[] = JHTML::_('Select.option',  'SI', 'Slovenia');
		$countries[] = JHTML::_('Select.option',  'SB', 'Solomon Islands');
		$countries[] = JHTML::_('Select.option',  'SO', 'Somalia');
		$countries[] = JHTML::_('Select.option',  'ZA', 'South Africa');
		$countries[] = JHTML::_('Select.option',  'GS', 'South Georgia and the South Sandwich Islands');
		$countries[] = JHTML::_('Select.option',  'ES', 'Spain');
		$countries[] = JHTML::_('Select.option',  'LK', 'Sri Lanka');
		$countries[] = JHTML::_('Select.option',  'SD', 'Sudan');
		$countries[] = JHTML::_('Select.option',  'SR', 'Suriname');
		$countries[] = JHTML::_('Select.option',  'SJ', 'Svalbard and Jan Mayen');
		$countries[] = JHTML::_('Select.option',  'SZ', 'Swaziland');
		$countries[] = JHTML::_('Select.option',  'SE', 'Sweden');
		$countries[] = JHTML::_('Select.option',  'CH', 'Switzerland');
		$countries[] = JHTML::_('Select.option',  'SY', 'Syrian Arab Republic');
		$countries[] = JHTML::_('Select.option',  'TW', 'Taiwan, Province of China');
		$countries[] = JHTML::_('Select.option',  'TJ', 'Tajikistan');
		$countries[] = JHTML::_('Select.option',  'TZ', 'Tanzania, United Republic Of');
		$countries[] = JHTML::_('Select.option',  'TH', 'Thailand');
		$countries[] = JHTML::_('Select.option',  'TL', 'Timor-Leste');
		$countries[] = JHTML::_('Select.option',  'TG', 'Togo');
		$countries[] = JHTML::_('Select.option',  'TK', 'Tokelau');
		$countries[] = JHTML::_('Select.option',  'TO', 'Tonga');
		$countries[] = JHTML::_('Select.option',  'TT', 'Trinidad and Tobago');
		$countries[] = JHTML::_('Select.option',  'TN', 'Tunisia');
		$countries[] = JHTML::_('Select.option',  'TR', 'Turkey');
		$countries[] = JHTML::_('Select.option',  'TM', 'Turkmenistan');
		$countries[] = JHTML::_('Select.option',  'TC', 'Turks and Caicos Islands');
		$countries[] = JHTML::_('Select.option',  'TV', 'Tuvalu');
		$countries[] = JHTML::_('Select.option',  'UG', 'Uganda');
		$countries[] = JHTML::_('Select.option',  'UA', 'Ukraine');
		$countries[] = JHTML::_('Select.option',  'AE', 'United Arab Emirates');
		$countries[] = JHTML::_('Select.option',  'GB', 'United Kingdom');
		$countries[] = JHTML::_('Select.option',  'UM', 'United States Minor Outlying Islands');
		$countries[] = JHTML::_('Select.option',  'UY', 'Uruguay');
		$countries[] = JHTML::_('Select.option',  'UZ', 'Uzbekistan');
		$countries[] = JHTML::_('Select.option',  'VU', 'Vanuatu');
		$countries[] = JHTML::_('Select.option',  'VE', 'Venezuela');
		$countries[] = JHTML::_('Select.option',  'VN', 'Viet Nam');
		$countries[] = JHTML::_('Select.option',  'VG', 'Virgin Islands, British');
		$countries[] = JHTML::_('Select.option',  'VI', 'Virgin Islands, U.S.');
		$countries[] = JHTML::_('Select.option',  'WF', 'Wallis and Futuna');
		$countries[] = JHTML::_('Select.option',  'EH', 'Western Sahara');
		$countries[] = JHTML::_('Select.option',  'YE', 'Yemen');
		$countries[] = JHTML::_('Select.option',  'ZM', 'Zambia');
		$countries[] = JHTML::_('Select.option',  'ZW', 'Zimbabwe');
		if ($required) $required = " required";
		else $required = "";
		return JHTML::_('select.genericlist',   $countries, $name, 'class="inputbox'.$required.'" size="1" ', 'value', 'text', $selected );
	}
}
?>