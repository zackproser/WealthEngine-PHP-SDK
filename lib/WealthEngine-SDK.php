<?php 

namespace WealthEngine\API;

//Load the Validator class
require_once(__DIR__ . '/lib/Validator.php'); 

/**
 * Handles interaction with the WealthEngine API 
 *
 * See dev.wealthengine.com for specifc documentation, API keys, etc.
 *
 * @author Zack Proser <zackproser@gmail.com>
 * 
 * @returns WealthEngine\API\HttpClient
 */
class HttpClient {

	/**
	 * User's WealthEngine API Key 
	 * @var string
	 */
	private $apiKey; 

	/**
	 * The production WealthEngine API Root
	 */
	CONST WEAPIROOT_PROD = 'https://api.wealthengine.com/v1/'; 

	/**
	 * The sandbox WealthEngine API Root
	 */
	CONST WEAPIROOT_SANDBOX = 'https://api-sandbox.wealthengine.com/v1/'; 

	/**
	 * The apiRoot to call - depending upon the requested environment
	 * @var string
	 */
	private $apiRoot; 

	/**
	 * Validation class
	 * @var WealthEngine\API\Validator
	 */
	private $validator;

	/**
	 * Instantiate and return the HttpClient class
	 * @param string $apiKey The user's API Key from dev.wealthengine.com
	 * @param string $env    The WealthEngine API environment that should be called
	 *
	 * @return  \WealthEngine\API\HttpClient
	 */
	public function __construct($apiKey, $env) 
	{
		$this->setAPIKey($apiKey); 

		$this->setEnvironment($env); 

		$this->validator = new Validator(); 

		return $this;
	}

	/**
	 * Validate and set the API Key passed to __construct function
	 * 
	 * @param string $apiKey The user's API Key from dev.wealthengine.com
	 *
	 * @return  void
	 */
	private function setAPIKey($apiKey)
	{
		if (gettype($apiKey) != 'string' || trim($apiKey) == '')
		{
			throw new Exception('You must pass a valid WealthEngine API Key (string) when instantiating the HttpClient class ' . __FILE__ . __METHOD__ . __LINE__);
		}

		$this->apiKey = $apiKey; 

		return;
	}

	/**
	 * Ensure the $env passed is a valid WealthEngine API environment
	 * 
	 * @param string $env name of the API environment that should be called
	 *
	 * @return  void
	 */
	private function setEnvironment($env)
	{

		if (gettype($env) != 'string' || trim($env) == '')
		{
			throw new Exception('Please specify either SANDBOX or PRODUCTION environment ' . __FILE__ . __METHOD__ . __LINE__); 
		}

		$env = trim(strtolower($env)); 

		//Set the desired environment 
		switch($env)
		{
			case 'prod': 
			case 'production': 
				$this->apiRoot = self::WEAPIROOT_PROD; 
				break; 
			case 'test':
			case 'sandbox':
				$this->apiRoot = self::WEAPIROOT_SANDBOX; 
				break; 
			default: 
				throw new Exception('You must set a valid environment: prod or test ' . __FILE__ . __METHOD__ . __LINE__); 
		}

		return;
	}

	/**
	 * Sets the default Http headers common to all requests
	 * 
	 * @param \Curl $ch The curl handle whose options will be set
	 *
	 * @return  \Curl $ch  The curl handle with common options set
	 */
	private function setDefaultHttpHeaders($ch)
	{
		$http_headers = array(
			'Content-Type: application/json',
			'Authorization: APIKey ' . $this->apiKey
		);

		//Set default curl options common to all requests
		curl_setopt($ch, CURLOPT_USERAGENT, 'WealthEngine PHP SDK'); 
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		#curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers); 

		return $ch; 
	}

	/**
	 * Attempt to lookup a WealthEngine Profile by email address and [names]
	 * @param  string $email_address the e-mail address to lookup
	 * @param  string $first_name    first name of the e-mail address's owner
	 * @param  string $last_name     last name of the e-mail address's owner
	 * 
	 * @return stdClass $result      object containing the API response
	 */
	public function getProfileByEmailAddress($email_address, $first_name = null, $last_name = null)
	{
		//Set the API url to POST to 
		$endpoint = $this->apiRoot . 'profile/find_one/by_email/basic'; 

		//Create the POST params object
		$post_fields = array(
			'email' => isset($email_address) ? $email_address : null, 
			'first_name' => isset($first_name) ? $first_name : null, 
			'last_name' => isset($last_name) ? $last_name  : null
		);

		$this->validator->validateEmailPostFields($post_fields); 

		//Encode the fields as json
		$post_values = json_encode($post_fields); 

		$ch = curl_init(); 
		//Set the default headers common to all requests
		$ch = $this->setDefaultHttpHeaders($ch); 

		curl_setopt($ch, CURLOPT_URL, $endpoint); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);

		//execute POST to the API
		$result = json_decode(curl_exec($ch));

		//Add http status code to the result for convenience
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$result->status_code = $status_code;  

		return $result;
	}

	/**
	 * Attempt to lookup a WealthEngine Profile by email address and [names]
	 * @param  string $first_name    first name of the address's owner
	 * @param  string $last_name     last name of the address's owner
	 * @param  string $address_line1 the address to lookup
	 * @param  string $city 		 the city where the address is located
	 * @param  string $state         the state where the address is located
	 * @param  integer $zip 		 the 5 digit zipcode where the address is located
	 * 
	 * @return stdClass $result      object containing the API response 
	 */
	public function getProfileByAddress($last_name, $first_name, $address_line1, $city, $state, $zip)
	{
		$endpoint = $this->apiRoot . 'profile/find_one/by_address/basic'; 

		$post_fields = array(
			'last_name' => isset($last_name) ? $last_name : null, 
			'first_name' => isset($first_name) ? $first_name : null, 
			'address_line1' => isset($address_line1) ? $address_line1 : null, 
			'city' => isset($city) ? $city : null, 
			'state' => isset($state) ? $state : null, 
			'zip' => isset($zip) ? $zip : null
		); 

		$this->validator->validateAddressPostFields($post_fields); 

		//Encode the fields as json
		$post_values = json_encode($post_fields); 

		$ch = curl_init(); 
		//Set the default headers common to all requests
		$ch = $this->setDefaultHttpHeaders($ch); 

		curl_setopt($ch, CURLOPT_URL, $endpoint); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values); 

		$result = json_decode(curl_exec($ch)); 

		//Add http status code to the result for convenience
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 

		$result->status_code = $status_code; 

		return $result; 
	}

	/**
	 * Attempt to lookup a WealthEngine Profile by email address and [names]
	 * @param  string $phone 		 a string containing the phone number in digits only
	 * @param  string $first_name    first name of the phone's owner
	 * @param  string $last_name     last name of the phones's owner
	 * 
	 * @return stdClass $result      object containing the API response
	 */
	public function getProfileByPhone($phone, $last_name = null, $first_name = null)
	{
		//Set endpoint for API call
		$endpoint = $this->apiRoot . 'profile/find_one/by_phone/basic'; 

		$post_fields = array(
			'phone' => $phone,
			'last_name' => isset($last_name) ? $last_name : null, 
			'first_name' => isset($first_name) ? $first_name : null
		); 

		$this->validator->validatePhonePostFields($post_fields);

		//Encode the fields as json
		$post_values = json_encode($post_fields); 

		$ch = curl_init(); 
		//Set the default headers common to all requests
		$ch = $this->setDefaultHttpHeaders($ch); 

		curl_setopt($ch, CURLOPT_URL, $endpoint); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values); 

		$result = json_decode(curl_exec($ch)); 

		//Add http status code to the result for convenience
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 

		$result->status_code = $status_code; 

		return $result; 
	}

	/**
	 * Attempt to create a session with the WealthEngine API to authorize future calls
	 * 
	 * @param  integer $duration 		an optional duration in milliseconds
	 * 
	 * @return stdClass $result         object containing the API response
	 */
	public function createSession($duration = 3600)
	{
		//Set endpoint for API call
		$endpoint = $this->apiRoot . 'session/create'; 

		//Session create endpoint takes one optional parameter - the desired session duration 
		$post_fields = array(
			'duration' => $duration
		);

		$this->validator->validateSessionPostFields($post_fields); 

		//Encode the post fields as json 
		$post_values = json_encode($post_fields); 

		$ch = curl_init(); 
		//Set the default headers common to all requests
		$ch = $this->setDefaultHttpHeaders($ch); 

		curl_setopt($ch, CURLOPT_URL, $endpoint); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);

		$result = json_decode(curl_exec($ch)); 

		//Add http status code to the result for convenience
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 

		$result->status_code = $status_code; 

		return $result; 

	}

}

?>
