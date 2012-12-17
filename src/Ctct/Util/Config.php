<?php
/**
 * Holds all configuration properties for the library. Should not be modified.
 * 
 * @package 	config
 * @author 		djellesma
 */
 
namespace Ctct\Util;

/**
 * Configuration class to hold endpoints, urls, errors messages etc.
 *
 * @package 	Util
 * @author 		Constant Contact
 */
class Config{

    /**
     * @var array - array of configuration properties
     */
    private static $props = array(

        /**
         * REST endpoints
         */
        'endpoints'	=> array(
		
			'base_url'		        => 'https://api.constantcontact.com/v2/',
			
			'contact'		                    => 'contacts/%s',
			'contacts'		                    => 'contacts',
			
			'lists'			                    => 'lists',
			'list'			                    => 'lists/%s',
            'list_contacts'                     => 'lists/%s/contacts',
			
			'contact_lists'	                    => 'contacts/%s/lists',
			'contact_list'	                    => 'contacts/%s/lists/%s',

            'campaigns'                         => 'campaigns',
            'campaign'   	                    => 'campaigns/%s',
            
			'campaign_schedules'	            => 'campaigns/%s/schedules',
			'campaign_schedule'		            => 'campaigns/%s/schedules/%s',
            'campaign_test_sends'               => 'campaigns/%s/tests',

            'campaign_tracking_summary'         => 'campaigns/%s/tracking/reports/summary',
            'campaign_tracking_bounces'         => 'campaigns/%s/tracking/bounces',
            'campaign_tracking_clicks'          => 'campaigns/%s/tracking/clicks',
            'campaign_tracking_forwards'        => 'campaigns/%s/tracking/forwards',
            'campaign_tracking_opens'           => 'campaigns/%s/tracking/opens',
            'campaign_tracking_sends'           => 'campaigns/%s/tracking/sends',
            'campaign_tracking_unsubscribes'    => 'campaigns/%s/tracking/unsubscribes',
            'campaign_tracking_link'            => 'campaigns/%s/tracking/clicks/%s',

            'contact_tracking_summary'         => 'contacts/%s/tracking/reports/summary',
            'contact_tracking_bounces'         => 'contacts/%s/tracking/bounces',
            'contact_tracking_clicks'          => 'contacts/%s/tracking/clicks',
            'contact_tracking_forwards'        => 'contacts/%s/tracking/forwards',
            'contact_tracking_opens'           => 'contacts/%s/tracking/opens',
            'contact_tracking_sends'           => 'contacts/%s/tracking/sends',
            'contact_tracking_unsubscribes'    => 'contacts/%s/tracking/unsubscribes',
            'contact_tracking_link'            => 'contacts/%s/tracking/clicks/%s'
				
		),

        /**
         * OAuth2 Authorization related configuration options
         */
        'auth'	=> array(
			'base_url'						=> 'https://oauth2.constantcontact.com/oauth2/',
			'response_type_code'			=> 'code',
			'response_type_token'			=> 'token',
			'authorization_code_grant_type'	=> 'authorization_code',
			'authorization_endpoint'		=> 'oauth/siteowner/authorize',
			'token_endpoint'				=> 'oauth/token'
			
		),

        /**
         * Errors to be returned for various exceptions
         */
        'errors'	=> array(
			'id_or_object'		=> 'Only an integer or %s object are allowed for this method.'
		)
	
	);
	
	/**
	 * Get a configuration property given a specified location, example usage: Config::get('auth.token_endpoint')
	 * @param $index - location of the property to obtain
	 * @return string
	 */
	 public static function get($index) 
	 {
		$index = explode('.', $index);
		return self::get_value($index, self::$props);
	 }
	 
	/**
	 * Navigate through a config array looking for a particular index
	 * @param array $index The index sequence we are navigating down
	 * @param array $value The portion of the config array to process
	 * @return mixed
	 */
	private static function get_value($index, $value)
	{
		if(is_array($index) and
		   count($index)) {
			$current_index = array_shift($index);
		}
		if(is_array($index) && count($index) && is_array($value[$current_index]) && count($value[$current_index])) {
			return self::get_value($index, $value[$current_index]);
		} else {
			return $value[$current_index];
		}
	}
}
