<?php
namespace Ctct\Util;

/**
 * Configuration class to hold endpoints, urls, errors messages etc.
 *
 * @package     Util
 * @author      Constant Contact
 */
class Config
{
    /**
     * @var array - array of configuration properties
     */
    private static $props = array(

        /**
         * REST endpoints
         */
        'endpoints' => array(

            'base_url' => 'https://api.constantcontact.com/v2/',
            'account_verified_addresses' => 'account/verifiedemailaddresses',
            'account_info' => 'account/info',
            'activity' => 'activities/%s',
            'activities' => 'activities',
            'export_contacts_activity' => 'activities/exportcontacts',
            'clear_lists_activity' => 'activities/clearlists',
            'remove_from_lists_activity' => 'activities/removefromlists',
            'add_contacts_activity' => 'activities/addcontacts',
            'contact' => 'contacts/%s',
            'contacts' => 'contacts',
            'lists' => 'lists',
            'list' => 'lists/%s',
            'list_contacts' => 'lists/%s/contacts',
            'contact_lists' => 'contacts/%s/lists',
            'contact_list' => 'contacts/%s/lists/%s',
            'campaigns' => 'emailmarketing/campaigns',
            'campaign' => 'emailmarketing/campaigns/%s',
            'campaign_schedules' => 'emailmarketing/campaigns/%s/schedules',
            'campaign_schedule' => 'emailmarketing/campaigns/%s/schedules/%s',
            'campaign_test_sends' => 'emailmarketing/campaigns/%s/tests',
            'campaign_tracking_summary' => 'emailmarketing/campaigns/%s/tracking/reports/summary',
            'campaign_tracking_bounces' => 'emailmarketing/campaigns/%s/tracking/bounces',
            'campaign_tracking_clicks' => 'emailmarketing/campaigns/%s/tracking/clicks',
            'campaign_tracking_forwards' => 'emailmarketing/campaigns/%s/tracking/forwards',
            'campaign_tracking_opens' => 'emailmarketing/campaigns/%s/tracking/opens',
            'campaign_tracking_sends' => 'emailmarketing/campaigns/%s/tracking/sends',
            'campaign_tracking_unsubscribes' => 'emailmarketing/campaigns/%s/tracking/unsubscribes',
            'campaign_tracking_link' => 'emailmarketing/campaigns/%s/tracking/clicks/%s',
            'contact_tracking_summary' => 'contacts/%s/tracking/reports/summary',
            'contact_tracking_bounces' => 'contacts/%s/tracking/bounces',
            'contact_tracking_clicks' => 'contacts/%s/tracking/clicks',
            'contact_tracking_forwards' => 'contacts/%s/tracking/forwards',
            'contact_tracking_opens' => 'contacts/%s/tracking/opens',
            'contact_tracking_sends' => 'contacts/%s/tracking/sends',
            'contact_tracking_unsubscribes' => 'contacts/%s/tracking/unsubscribes',
            'contact_tracking_link' => 'contacts/%s/tracking/clicks/%s',
            'library_files' => 'library/files',
            'library_file' => 'library/files/%s',
            'library_folders' => 'library/folders',
            'library_folder' => 'library/folders/%s',
            'library_files_by_folder' => 'library/folders/%s/files'

        ),
        /**
         * Column names used with bulk activities
         */
        'activities_columns' => array(
            'email' => 'EMAIL',
            'first_name' => 'FIRST NAME',
            'last_name' => 'LAST NAME',
            'birthday_day' => 'BIRTHDAY_DAY',
            'birthday_month' => 'BIRTHDAY_MONTH',
            'anniversary' => 'ANNIVERSARY',
            'job_title' => 'JOB TITLE',
            'company_name' => 'COMPANY NAME',
            'work_phone' => 'WORK PHONE',
            'home_phone' => 'HOME PHONE',
            'address1' => 'ADDRESS LINE 1',
            'address2' => 'ADDRESS LINE 2',
            'address3' => 'ADDRESS LINE 3',
            'city' => 'CITY',
            'state' => 'STATE',
            'state_province' => 'US STATE/CA PROVINCE',
            'country' => 'COUNTRY',
            'postal_code' => 'ZIP/POSTAL CODE',
            'sub_postal_code' => 'SUB ZIP/POSTAL CODE',
            'custom_field_1' => 'CUSTOM FIELD 1',
            'custom_field_2' => 'CUSTOM FIELD 2',
            'custom_field_3' => 'CUSTOM FIELD 3',
            'custom_field_4' => 'CUSTOM FIELD 4',
            'custom_field_5' => 'CUSTOM FIELD 5',
            'custom_field_6' => 'CUSTOM FIELD 6',
            'custom_field_7' => 'CUSTOM FIELD 7',
            'custom_field_8' => 'CUSTOM FIELD 8',
            'custom_field_9' => 'CUSTOM FIELD 9',
            'custom_field_10' => 'CUSTOM FIELD 10',
            'custom_field_11' => 'CUSTOM FIELD 11',
            'custom_field_12' => 'CUSTOM FIELD 12',
            'custom_field_13' => 'CUSTOM FIELD 13',
            'custom_field_14' => 'CUSTOM FIELD 14',
            'custom_field_15' => 'CUSTOM FIELD 15',
        ),
        /**
         * OAuth2 Authorization related configuration options
         */
        'auth' => array(
            'base_url' => 'https://oauth2.constantcontact.com/oauth2/',
            'response_type_code' => 'code',
            'response_type_token' => 'token',
            'authorization_code_grant_type' => 'authorization_code',
            'authorization_endpoint' => 'oauth/siteowner/authorize',
            'token_endpoint' => 'oauth/token',
            'token_info' => 'tokeninfo.htm'
        ),
        /**
         * Errors to be returned for various exceptions
         */
        'errors'    => array(
            'id_or_object'        => 'Only an id or %s object are allowed for this method.'
        ),
        
        /**
         * Setting the version fo the application used in Rest Calls when setting the version header
         */
        'settings'    => array(
            'version'        => '1.3.0'
        ),
    );

    /**
     * Get a configuration property given a specified location, example usage: Config::get('auth.token_endpoint')
     * @param $index - location of the property to obtain
     * @return string
     */
    public static function get($index)
    {
        $index = explode('.', $index);
        return self::getValue($index, self::$props);
    }

    /**
     * Navigate through a config array looking for a particular index
     * @param array $index The index sequence we are navigating down
     * @param array $value The portion of the config array to process
     * @return mixed
     */
    private static function getValue($index, $value)
    {
        if (is_array($index) && count($index)) {
            $current_index = array_shift($index);
        }
        if (is_array($index) && count($index) && is_array($value[$current_index]) && count($value[$current_index])) {
            return self::getValue($index, $value[$current_index]);
        } else {
            return $value[$current_index];
        }
    }
}
