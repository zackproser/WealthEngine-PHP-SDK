<?php 

namespace WealthEngine\API; 

/**
 * Handles validation of POST parameters to WealthEngine API 
 * 
 * @class Validator
 * @author  Zack Proser <zackproser@gmail.com>
 */
class Validator {

	/**
	 * Validates POST parameters required by by_email/basic endpoint
	 * 
	 * @param  array $post_fields associative array containing POST params
	 * @return bool  Whether or not the $post_fields object is valid        
	 *
	 * @throws \Exception when params are invalid
	 */
	public function validateEmailPostFields($post_fields)
		{
			//Validate email post fields
			if ($post_fields['email'] == null 
				|| trim($post_fields['email']) == '' 
				|| gettype($post_fields['email']) != 'string'
			)
			{
				throw new \Exception('You must pass a valid email address as a string to getProfileByEmailAddress'); 
			}	

			$this->validateNameParameters($post_fields); 

			return true; 
		}

	/**
	 * Validates POST parameters required by by_phone/basic endpoint
	 * 
	 * @param  array $post_fields associative array containing POST params
	 * @return bool  Whether or not the $post_fields object is valid        
	 *
	 * @throws \Exception when params are invalid
	 */
	public function validatePhonePostFields($post_fields)
	{
		//Validate phone number
		if ($post_fields['phone'] == null 
			|| trim($post_fields['phone'] == '') 
			|| gettype($post_fields['phone']) != 'string'
		)
		{
			throw new \Exception('You must pass a valid string as a phone number'); 
		}

		if (preg_match('/^\d+$/', $post_fields['phone']) != 1)
		{
			throw new \Exception('Phone number strings must contain digits only - no special characters');
		}

		$this->validateNameParameters($post_fields); 

		return true; 
	}

	/**
	 * Validates POST parameters required by by_address/basic endpoint
	 * 
	 * @param  array $post_fields associative array containing POST params
	 * @return bool  Whether or not the $post_fields object is valid        
	 *
	 * @throws \Exception when params are invalid
	 */
	public function validateAddressPostFields($post_fields)
	{

		foreach($post_fields as $field => $value)
		{

			if ($field == 'zip')
			{

				var_dump(gettype($value) != 'integer');

				if (gettype($value) != 'integer'
					|| strlen((string)$value) != 5 
					|| $value <= 0)
				{
					throw new \Exception('getProfileByAddress requires zipcode to be a valid 5 digit integer'); 
				}
			}
			else if (!isset($value) 
				|| gettype($value) != 'string'
				|| trim($value) == ''
			)
			{
				throw new \Exception('getProfileByAddress method <requires> the following variables to be valid strings: last_name, first_name, address_line1, city, and state');
			}
		}

		$this->validateNameParameters($post_fields); 

		return true; 
	}

	/**
	 * Validates POST parameters required by session/create endpoint
	 * 
	 * @param  array $post_fields associative array containing POST params
	 * @return bool  Whether or not the $post_fields object is valid        
	 *
	 * @throws \Exception when params are invalid
	 */
	public function validateSessionPostFields($post_fields)
	{
		if (isset($post_fields['duration']))
		{
			if (gettype($post_fields['duration']) != 'integer'
				|| intval($post_fields['duration'] < 0)
			)
			{
				throw new \Exception('Session duration parameter must be an integer representing the requested duration in milliseconds'); 
			}
		}
	}

	/**
	 * Validates first_name and last_name params - optional to various API calls
	 * 
	 * @param  array $post_fields associative array containing POST params
	 * @return bool  Whether or not the $first_name and $last_name params are valid - only if they are set      
	 *
	 * @throws \Exception when params are invalid
	 */
	public function validateNameParameters($post_fields)
	{
		if (isset($post_fields['first_name'])){
			if (gettype($post_fields['first_name']) != 'string') {
				throw new \Exception('The first_name parameter must be a valid string'); 
			}
		}

		if (isset($post_fields['last_name'])){
			if (gettype($post_fields['last_name']) != 'string'){
				throw new \Exception('The last_name parameter must be a valid string'); 
			}
		}

		return true; 
	}

}

?>

