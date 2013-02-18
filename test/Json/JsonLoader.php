<?php

class JsonLoader
{
	
	const CONTACTS_FOLDER = "/Contacts";
	const LISTS_FOLDER = "/Lists";
	const CAMPAIGNS_FOLDER = "/Campaigns";
    const CAMPAIGN_TRACKING_FOLDER = "/Tracking";
    const ACTIVITES_FOLDER = "/Activities";
	
	public static function getContactJson()
	{
		return file_get_contents(__DIR__ . self::CONTACTS_FOLDER . "/get_contact.json");
	}
	
	public static function getContactsJson()
	{
		return file_get_contents(__DIR__ . self::CONTACTS_FOLDER . "/get_contacts.json");
	}

    public static function getContactsNoNextJson()
    {
        return file_get_contents(__DIR__ . self::CONTACTS_FOLDER . "/get_contacts_no_next.json");
    }
	
	public static function getListsJson()
	{
		return file_get_contents(__DIR__ . self::LISTS_FOLDER . "/get_lists.json");
	}
	
	public static function getListJson()
	{
		return file_get_contents(__DIR__ . self::LISTS_FOLDER . "/get_list.json");
	}
	
	public static function getCampaignJson()
	{
		return file_get_contents(__DIR__ . self::CAMPAIGNS_FOLDER . "/get_campaign.json");
	}
	
	public static function getCampaignsJson()
	{
		return file_get_contents(__DIR__ . self::CAMPAIGNS_FOLDER . "/get_campaigns.json");
	}

    public static function getCampaignScheduleJson()
    {
        return file_get_contents(__DIR__ . self::CAMPAIGNS_FOLDER . "/get_schedule.json");
    }

    public static function getCampaignSchedulesJson()
    {
        return file_get_contents(__DIR__ . self::CAMPAIGNS_FOLDER . "/get_schedules.json");
    }

    public static function getTestSendJson()
    {
        return file_get_contents(__DIR__ . self::CAMPAIGNS_FOLDER . "/post_test_send.json");
    }

    public static function getClicks()
    {
        return file_get_contents(__DIR__ . self::CAMPAIGN_TRACKING_FOLDER . "/get_clicks.json");
    }

    public static function getBounces()
    {
        return file_get_contents(__DIR__ . self::CAMPAIGN_TRACKING_FOLDER . "/get_bounces.json");
    }

    public static function getForwards()
    {
        return file_get_contents(__DIR__ . self::CAMPAIGN_TRACKING_FOLDER . "/get_forwards.json");
    }

    public static function getOptOuts()
    {
        return file_get_contents(__DIR__ . self::CAMPAIGN_TRACKING_FOLDER . "/get_opt_outs.json");
    }

    public static function getSends()
    {
        return file_get_contents(__DIR__ . self::CAMPAIGN_TRACKING_FOLDER . "/get_sends.json");
    }

    public static function getOpens()
    {
        return file_get_contents(__DIR__ . self::CAMPAIGN_TRACKING_FOLDER . "/get_opens.json");
    }

    public static function getSummary()
    {
        return file_get_contents(__DIR__ . self::CAMPAIGN_TRACKING_FOLDER . "/get_summary.json");
    }

    public static function getActivities()
    {
        return file_get_contents(__DIR__ . self::ACTIVITES_FOLDER . "/get_activities.json");
    }

    public static function getActivity()
    {
        return file_get_contents(__DIR__ . self::ACTIVITES_FOLDER . "/get_activity.json");
    }

    public static function getClearListsActivity()
    {
        return file_get_contents(__DIR__ . self::ACTIVITES_FOLDER . "/post_clear_lists.json");
    }

    public static function getExportContactsActivity()
    {
        return file_get_contents(__DIR__ . self::ACTIVITES_FOLDER . "/post_export_contacts.json");
    }

    public static function getRemoveContactsFromListsActivity()
    {
        return file_get_contents(__DIR__ . self::ACTIVITES_FOLDER . "/post_remove_contacts_from_lists.json");
    }

    public static function getAddContactsActivity()
    {
        return file_get_contents(__DIR__ . self::ACTIVITES_FOLDER . "/post_add_contacts.json");
    }
}
