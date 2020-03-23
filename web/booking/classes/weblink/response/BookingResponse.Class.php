<?php

namespace weblink;

/* Example

    string(1081) "<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><createBookingResponse xmlns="http://www.instantsoftware.com/SecureWeblinkPlusAPI">

<createBookingResult>
<dblTotalGoods>530</dblTotalGoods>
<dblTotalTax>53</dblTotalTax>
<dblTotalCost>583</dblTotalCost>
<blnHasCSA>false</blnHasCSA>
<dblCSAAmount>0</dblCSAAmount>
<dblDues>583</dblDues>
<arrCharges>
    <clsCharge><strDesc>Rent</strDesc><dblAmount>465</dblAmount>
    <dblTax>46.5</dblTax><enumType>Rent</enumType></clsCharge><clsCharge><strDesc>Damage Waiver</strDesc><dblAmount>65</dblAmount><dblTax>6.5</dblTax><enumType>Other</enumType></clsCharge>
</arrCharges>
<arrPayments>
    <clsPayment><dtDuedate>2016-11-15T00:00:00</dtDuedate><dblAmount>583</dblAmount></clsPayment>
</arrPayments>
<strBookingNo>3</strBookingNo>
<objCSAInfo><blnIsAccepted>false</blnIsAccepted><dblPolicyAmt>0</dblPolicyAmt></objCSAInfo>
</createBookingResult></createBookingResponse></soap:Body></soap:Envelope>"
*/

class BookingResponse extends WeblinkResponse
{
	protected $data=null;

        const resultname = 'createBookingResult';
	
	public function __construct($result)
	{
		parent::__construct($result, self::resultname);
	}

        /*
         * If the booking was successful
         * @return bool
         */
        public function successful() {
            return isset($this->data['strBookingNo']) && $this->data['strBookingNo'] != '';
        }

	public function getvalue($name)
	{
		switch($name)
		{
                    case 'confirmation':
                        return isset($this->data['strBookingNo']) ? $this->data['strBookingNo'] : null;
                    break;
/*
                    case 'status':
			return $this->data->Result->resultStatusFlag;
			break;
*/
		default:
			Throw new Exception('Unknown booking response value: '.$name, 9996);
		}
	}
}
