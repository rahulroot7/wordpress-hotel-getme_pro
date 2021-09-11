<?php
global $HotelApi;
class HotelApi{
	protected $key_id = 3621;
    protected $api_key = 'ca7b9854-fb75-4ffd-b98e-163560efb7c1';
    protected $language = 'en';
    public $currency = 'USD';
    // public function __construct() {
    // {
    // 	$this->currency = file_get_contents('https://ipapi.co/'.$_SERVER["REMOTE_ADDR"].'/currency/');
    // }
    public function get_searched_hotels($address){
	    $hotel['language'] = $this->language;
	    $hotel['query'] = $address;
	    $curl = curl_init();
	    curl_setopt_array($curl, array(
	      CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/search/multicomplete/',
	      CURLOPT_RETURNTRANSFER => true,
	      CURLOPT_ENCODING => '',
	      CURLOPT_MAXREDIRS => 10,
	      CURLOPT_TIMEOUT => 0,
	      CURLOPT_FOLLOWLOCATION => true,
	      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	      CURLOPT_CUSTOMREQUEST => 'POST',
	      CURLOPT_POSTFIELDS =>json_encode($hotel),
	      CURLOPT_HTTPHEADER => array(
	        'Authorization: Basic '. base64_encode("$this->key_id:$this->api_key"),
	        'Content-Type: application/json'
	      ),
	    ));

	    $response = curl_exec($curl);

	    curl_close($curl);
	    $response = json_decode($response,true);
	    $hotels = $response['data']['hotels'];
	    return array_column($hotels,'id');
	}
	public function get_hotel_info($id){

	    $hotel['language'] = $this->language;
	    $hotel['id'] = $id;
	    $hotel = json_encode($hotel);
	    $curl = curl_init();
	    curl_setopt_array($curl, array(
	    CURLOPT_URL => "https://api.worldota.net/api/b2b/v3/hotel/info/?data=$hotel",
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_ENCODING => '',
	    CURLOPT_MAXREDIRS => 10,
	    CURLOPT_TIMEOUT => 0,
	    CURLOPT_FOLLOWLOCATION => true,
	    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	    CURLOPT_CUSTOMREQUEST => 'GET',
	      CURLOPT_HTTPHEADER => array(
	      'Authorization: Basic '. base64_encode("$this->key_id:$this->api_key"),
	      'Content-Type: application/json'
	    ),
	  ));

	  $response = curl_exec($curl);

	  curl_close($curl);
	  return json_decode($response)->data;
	}

	public function get_hotel_page($search,$id){
	  extract($search);
	  $hotel['checkin'] = explode(' - ',$from_to)[0];
	  $hotel['checkout'] = explode(' - ',$from_to)[1];
	  $hotel['guests'] = array_map(array($this,'nestedinteger'), array_values($rooms));
	  $hotel['id'] = $id;
	  $hotel['language'] = $this->language;
	  $hotel['currency'] = $this->currency;
	  $curl = curl_init();

	  curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/search/hp/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>json_encode($hotel),
			CURLOPT_HTTPHEADER => array(
			'Authorization: Basic '. base64_encode("$this->key_id:$this->api_key"),
			'Content-Type: application/json'
			),
	    ));

	    $response = curl_exec($curl);

	    curl_close($curl);
	    $hotels = json_decode($response);
	    if(isset($hotels->data->hotels[0])){
	    	return $hotels->data->hotels[0];
	    }else{
	    	return '';
	    }

	}
	public function get_hotel_static($type,$staticKey,$lang='en'){
	  $curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/hotel/static/',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
	      'Authorization: Basic '. base64_encode("$this->key_id:$this->api_key"),
	      'Content-Type: application/json'
	    	),
		));

		$response = curl_exec($curl);

		curl_close($curl);
	  $staticData =  json_decode($response)->data->$type;
	  foreach ($staticData as $key => $value) {
	  	if($value->name == $staticKey){
	  		return $value->locale->$lang;
	  	}
	  }

	}
	public function nestedinteger($value) {
	    if (is_array($value)) {
	        return array_map(array($this,'nestedinteger'), $value);
	    }
	    return (int)$value;
	}
	public function get_currency_symbol($code = "USD")
	{
		$currency_symbols=array('AED'=>'&#1583;.&#1573;','AFN'=>'&#65;&#102;','ALL'=>'&#76;&#101;&#107;','AMD'=>'','ANG'=>'&#402;','AOA'=>'&#75;&#122;','ARS'=>'&#36;','AUD'=>'&#36;','AWG'=>'&#402;','AZN'=>'&#1084;&#1072;&#1085;','BAM'=>'&#75;&#77;','BBD'=>'&#36;','BDT'=>'&#2547;','BGN'=>'&#1083;&#1074;','BHD'=>'.&#1583;.&#1576;','BIF'=>'&#70;&#66;&#117;','BMD'=>'&#36;','BND'=>'&#36;','BOB'=>'&#36;&#98;','BRL'=>'&#82;&#36;','BSD'=>'&#36;','BTN'=>'&#78;&#117;&#46;','BWP'=>'&#80;','BYR'=>'&#112;&#46;','BZD'=>'&#66;&#90;&#36;','CAD'=>'&#36;','CDF'=>'&#70;&#67;','CHF'=>'&#67;&#72;&#70;','CLF'=>'','CLP'=>'&#36;','CNY'=>'&#165;','COP'=>'&#36;','CRC'=>'&#8353;','CUP'=>'&#8396;','CVE'=>'&#36;','CZK'=>'&#75;&#269;','DJF'=>'&#70;&#100;&#106;','DKK'=>'&#107;&#114;','DOP'=>'&#82;&#68;&#36;','DZD'=>'&#1583;&#1580;','EGP'=>'&#163;','ETB'=>'&#66;&#114;','EUR'=>'&#8364;','FJD'=>'&#36;','FKP'=>'&#163;','GBP'=>'&#163;','GEL'=>'&#4314;','GHS'=>'&#162;','GIP'=>'&#163;','GMD'=>'&#68;','GNF'=>'&#70;&#71;','GTQ'=>'&#81;','GYD'=>'&#36;','HKD'=>'&#36;','HNL'=>'&#76;','HRK'=>'&#107;&#110;','HTG'=>'&#71;','HUF'=>'&#70;&#116;','IDR'=>'&#82;&#112;','ILS'=>'&#8362;','INR'=>'&#8377;','IQD'=>'&#1593;.&#1583;','IRR'=>'&#65020;','ISK'=>'&#107;&#114;','JEP'=>'&#163;','JMD'=>'&#74;&#36;','JOD'=>'&#74;&#68;','JPY'=>'&#165;','KES'=>'&#75;&#83;&#104;','KGS'=>'&#1083;&#1074;','KHR'=>'&#6107;','KMF'=>'&#67;&#70;','KPW'=>'&#8361;','KRW'=>'&#8361;','KWD'=>'&#1583;.&#1603;','KYD'=>'&#36;','KZT'=>'&#1083;&#1074;','LAK'=>'&#8365;','LBP'=>'&#163;','LKR'=>'&#8360;','LRD'=>'&#36;','LSL'=>'&#76;','LTL'=>'&#76;&#116;','LVL'=>'&#76;&#115;','LYD'=>'&#1604;.&#1583;','MAD'=>'&#1583;.&#1605;.','MDL'=>'&#76;','MGA'=>'&#65;&#114;','MKD'=>'&#1076;&#1077;&#1085;','MMK'=>'&#75;','MNT'=>'&#8366;','MOP'=>'&#77;&#79;&#80;&#36;','MRO'=>'&#85;&#77;','MUR'=>'&#8360;','MVR'=>'.&#1923;','MWK'=>'&#77;&#75;','MXN'=>'&#36;','MYR'=>'&#82;&#77;','MZN'=>'&#77;&#84;','NAD'=>'&#36;','NGN'=>'&#8358;','NIO'=>'&#67;&#36;','NOK'=>'&#107;&#114;','NPR'=>'&#8360;','NZD'=>'&#36;','OMR'=>'&#65020;','PAB'=>'&#66;&#47;&#46;','PEN'=>'&#83;&#47;&#46;','PGK'=>'&#75;','PHP'=>'&#8369;','PKR'=>'&#8360;','PLN'=>'&#122;&#322;','PYG'=>'&#71;&#115;','QAR'=>'&#65020;','RON'=>'&#108;&#101;&#105;','RSD'=>'&#1044;&#1080;&#1085;&#46;','RUB'=>'&#1088;&#1091;&#1073;','RWF'=>'&#1585;.&#1587;','SAR'=>'&#65020;','SBD'=>'&#36;','SCR'=>'&#8360;','SDG'=>'&#163;','SEK'=>'&#107;&#114;','SGD'=>'&#36;','SHP'=>'&#163;','SLL'=>'&#76;&#101;','SOS'=>'&#83;','SRD'=>'&#36;','STD'=>'&#68;&#98;','SVC'=>'&#36;','SYP'=>'&#163;','SZL'=>'&#76;','THB'=>'&#3647;','TJS'=>'&#84;&#74;&#83;','TMT'=>'&#109;','TND'=>'&#1583;.&#1578;','TOP'=>'&#84;&#36;','TRY'=>'&#8356;','TTD'=>'&#36;','TWD'=>'&#78;&#84;&#36;','TZS'=>'','UAH'=>'&#8372;','UGX'=>'&#85;&#83;&#104;','USD'=>'&#36;','UYU'=>'&#36;&#85;','UZS'=>'&#1083;&#1074;','VEF'=>'&#66;&#115;','VND'=>'&#8363;','VUV'=>'&#86;&#84;','WST'=>'&#87;&#83;&#36;','XAF'=>'&#70;&#67;&#70;&#65;','XCD'=>'&#36;','XDR'=>'','XOF'=>'','XPF'=>'&#70;','YER'=>'&#65020;','ZAR'=>'&#82;','ZMK'=>'&#90;&#75;','ZWL'=>'&#90;&#36;',);
		return $currency_symbols[$code];
	}
	public function get_long_lat($address){
	    $coordinates = [];
	    $address = str_replace(" ", "+", $address);
	    $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false&key=AIzaSyAD4iKa3ykfkEZWS-wgY38pq96-LD495fU');
	    $output= json_decode($geocode);

	    $coordinates['lat'] = $output->results[0]->geometry->location->lat;
	    $coordinates['long'] = $output->results[0]->geometry->location->lng;
	    $coordinates['radius'] =$this->distance($output->results[0]->geometry->viewport->northeast->lat,$output->results[0]->geometry->viewport->northeast->lng,$output->results[0]->geometry->viewport->southwest->lat,$output->results[0]->geometry->viewport->southwest->lng);
	    return $coordinates;
	}
	function distance($lat1, $lon1, $lat2, $lon2) {
		  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
		    return 0;
		  }
		  else {
		    $theta = $lon1 - $lon2;
		    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		    $dist = acos($dist);
		    $dist = rad2deg($dist);
		    $miles = $dist * 60 * 1.1515;

		  	$miles=$miles * 1.609344*1000;
		  	$miles=ceil($miles);

		      return ($miles>70000)?70000:$miles;

		  }
		}
	public function get_available_hotel($search){

	  extract($search);
	  $coordinates = $this->get_long_lat($address);
	  $hotel['checkin'] = explode(' - ',$from_to)[0];
	  $hotel['checkout'] = explode(' - ',$from_to)[1];
	  $hotel['guests'] = array_map(array($this,'nestedinteger'), array_values($rooms));
	  $hotel['longitude'] = (float) $coordinates['long'];
	  $hotel['latitude'] = (float) $coordinates['lat'];
	  $hotel['radius'] = $coordinates['radius'];
	  $hotel['currency'] = $this->currency;
	  $hotel['language'] = $this->language;

	  $curl = curl_init();
	  curl_setopt_array($curl, array(
	    CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/search/serp/geo/',
	    CURLOPT_RETURNTRANSFER => true,
	      CURLOPT_ENCODING => '',
	      CURLOPT_MAXREDIRS => 10,
	      CURLOPT_TIMEOUT => 0,
	      CURLOPT_FOLLOWLOCATION => true,
	      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	      CURLOPT_CUSTOMREQUEST => 'POST',
	      CURLOPT_POSTFIELDS =>json_encode($hotel),
	      CURLOPT_HTTPHEADER => array(
	        'Authorization: Basic '. base64_encode("$this->key_id:$this->api_key"),
	        'Content-Type: application/json'
	      ),
	    ));

	    $response = curl_exec($curl);

	    curl_close($curl);

	  return json_decode($response)->data;
	}
	public function GetRoomImages($id, $array) {
	   foreach ($array as $key => $val) {
	       if ($val->name_struct->main_name === $id) {
	           return $array[$key];
	       }
	   }
	   return null;
	}
	public function GetPayments($id, $array) {
	   foreach ($array as $key => $val) {
	       if ($val->type === $id) {
	           return $array[$key];
	       }
	   }
	   return null;
	}
	public function plural($amount)
    {
        return ($amount == 1)?'':'s';
    }
    public function uuid($data = null)
    {
    	$data = $data ?? random_bytes(16);
	    assert(strlen($data) == 16);
	    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
	    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    public function book_room($hash){
	  $hotel['partner_order_id'] = $this->uuid();
	  $hotel['book_hash'] = $hash;
	  $hotel['language'] = $this->language;
	  $hotel['user_ip'] = $_SERVER['REMOTE_ADDR'];

	  $curl = curl_init();
	  curl_setopt_array($curl, array(
	    CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/hotel/order/booking/form/',
	    CURLOPT_RETURNTRANSFER => true,
	      CURLOPT_ENCODING => '',
	      CURLOPT_MAXREDIRS => 10,
	      CURLOPT_TIMEOUT => 0,
	      CURLOPT_FOLLOWLOCATION => true,
	      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	      CURLOPT_CUSTOMREQUEST => 'POST',
	      CURLOPT_POSTFIELDS =>json_encode($hotel),
	      CURLOPT_HTTPHEADER => array(
	        'Authorization: Basic '. base64_encode("$this->key_id:$this->api_key"),
	        'Content-Type: application/json'
	      ),
	    ));

	    $response = curl_exec($curl);
	    curl_close($curl);

	  return json_decode($response)->data;
	}
	public function payment($item_id){
	  $hotel['object_id'] = (string)$item_id;
	  $hotel['pay_uuid'] = (string)$this->uuid();
	  $hotel['init_uuid'] = (string)$this->uuid();
	  $hotel['user_first_name'] = 'Name';
	  $hotel['user_last_name'] = 'Ostrovok';
	  $hotel['cvc'] = '132';
	  $hotel['is_cvc_required'] = true;
	  $hotel['credit_card_data_core'] = ["year"=> "25",
        "card_number"=> "4111111111111111",
        "card_holder"=> "TEST",
        "month"=> "01"];

	  $curl = curl_init();
	  curl_setopt_array($curl, array(
	    CURLOPT_URL => 'https://api.payota.net/api/public/v1/manage/init_partners',
	    CURLOPT_RETURNTRANSFER => true,
	      CURLOPT_ENCODING => '',
	      CURLOPT_MAXREDIRS => 10,
	      CURLOPT_TIMEOUT => 0,
	      CURLOPT_FOLLOWLOCATION => true,
	      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	      CURLOPT_CUSTOMREQUEST => 'POST',
	      CURLOPT_POSTFIELDS =>json_encode($hotel),
	      CURLOPT_HTTPHEADER => array(
	        'Authorization: Basic '. base64_encode("$this->key_id:$this->api_key"),
	        'Content-Type: application/json'
	      ),
	    ));

	    $response = curl_exec($curl);
	    curl_close($curl);

	  return $hotel;
	}

	public function booking_finish($hotel){

	$curl = curl_init();
	  curl_setopt_array($curl, array(
	    CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/hotel/order/booking/finish/',
	    CURLOPT_RETURNTRANSFER => true,
	      CURLOPT_ENCODING => '',
	      CURLOPT_MAXREDIRS => 10,
	      CURLOPT_TIMEOUT => 0,
	      CURLOPT_FOLLOWLOCATION => true,
	      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	      CURLOPT_CUSTOMREQUEST => 'POST',
	      CURLOPT_POSTFIELDS =>$hotel,
	      CURLOPT_HTTPHEADER => array(
	        'Authorization: Basic '. base64_encode("$this->key_id:$this->api_key"),
	        'Content-Type: application/json'
	      ),
	    ));

	    $response = curl_exec($curl);
	    curl_close($curl);


	  return json_decode($response);
	}

	public function booking_finish_status($hotel){

	$curl = curl_init();
	  curl_setopt_array($curl, array(
	    CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/hotel/order/booking/finish/status/',
	    CURLOPT_RETURNTRANSFER => true,
	      CURLOPT_ENCODING => '',
	      CURLOPT_MAXREDIRS => 10,
	      CURLOPT_TIMEOUT => 0,
	      CURLOPT_FOLLOWLOCATION => true,
	      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	      CURLOPT_CUSTOMREQUEST => 'POST',
	      CURLOPT_POSTFIELDS =>$hotel,
	      CURLOPT_HTTPHEADER => array(
	        'Authorization: Basic '. base64_encode("$this->key_id:$this->api_key"),
	        'Content-Type: application/json'
	      ),
	    ));

	    $response = curl_exec($curl);

	    curl_close($curl);

	  return json_decode($response)->data;;
	}

	public function create_user($user_id)
	{
		$user = get_userdata( $user_id );

		$hotel['type'] = 'self_booker';
	  	$hotel['email'] = $user->user_email;
	  	$hotel['first_name'] = $user->first_name;
	  	$hotel['last_name'] = $user->last_name;
		$curl = curl_init();
	  	curl_setopt_array($curl, array(
	    	CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/profiles/create/',
	    	CURLOPT_RETURNTRANSFER => true,
	      	CURLOPT_ENCODING => '',
	      	CURLOPT_MAXREDIRS => 10,
	      	CURLOPT_TIMEOUT => 0,
	      	CURLOPT_FOLLOWLOCATION => true,
	      	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	      	CURLOPT_CUSTOMREQUEST => 'POST',
	      	CURLOPT_POSTFIELDS =>json_encode($hotel),
	      	CURLOPT_HTTPHEADER => array(
	        'Authorization: Basic '. base64_encode("$this->key_id:$this->api_key"),
	        'Content-Type: application/json'
	      ),
	    ));

	    $response = curl_exec($curl);
	    curl_close($curl);
	  return json_decode($response);
	}

	public function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {
		$dates = array();
	    $current = strtotime($first);
	    $last = strtotime($last);

	    while( $current < $last ) {

	        $dates[] = date($output_format, $current);
	        $current = strtotime($step, $current);
	    }
		return $dates;
	}
	public function search_order(){

		  $search['ordering'] = ['ordering_type' => 'asc',
                              'ordering_by' => 'checkin_at',
                            ];
          $search['pagination'] = ['page_size' => '50',
                              'page_number' => '1',
                            ];

		  $curl = curl_init();
		  curl_setopt_array($curl, array(
		    CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/hotel/order/info/',
		    CURLOPT_RETURNTRANSFER => true,
		      CURLOPT_ENCODING => '',
		      CURLOPT_MAXREDIRS => 10,
		      CURLOPT_TIMEOUT => 0,
		      CURLOPT_FOLLOWLOCATION => true,
		      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		      CURLOPT_CUSTOMREQUEST => 'POST',
		      CURLOPT_POSTFIELDS =>json_encode($search),
		      CURLOPT_HTTPHEADER => array(
		        'Authorization: Basic '. base64_encode("$this->key_id:$this->api_key"),
		        'Content-Type: application/json'
		      ),
		    ));

		    $response = curl_exec($curl);

		    curl_close($curl);

		  return json_decode($response);
	}
}
$HotelApi = new HotelApi();

?>