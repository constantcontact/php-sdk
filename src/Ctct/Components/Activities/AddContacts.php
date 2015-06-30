<?php
namespace Ctct\Components\Activities;

use Ctct\Components\Component;
use Ctct\Util\Config;
use Ctct\Exceptions\IllegalArgumentException;

/**
 * Represents an AddContacts Activity
 *
 * @package        Components
 * @subpackage     Activities
 * @author         Constant Contact
 */
class AddContacts extends Component
{
    public $import_data = array();
    public $lists = array();
    public $column_names = array();

    public function __construct(Array $contacts, Array $lists, Array $columnNames = array())
    {
        if (!empty($contacts)) {
            if ($contacts[0] instanceof AddContactsImportData) {
                $this->import_data = $contacts;
            } else {
                $msg = sprintf(Config::get('errors.id_or_object'), "AddContactsImportData");
                throw new IllegalArgumentException($msg);
            }
        }

        $this->lists = $lists;

        if (empty($columnNames)) {
            $usedColumns[] = Config::get('activities_columns.email');
            $contact = $contacts[0];

            if (isset($contact->first_name)) {
                $usedColumns[] = Config::get('activities_columns.first_name');
            }
            if (isset($contact->last_name)) {
                $usedColumns[] = Config::get('activities_columns.last_name');
            }
            if (isset($contact->birthday_day)) {
                $usedColumns[] = Config::get('activities_columns.birthday_day');
            }
            if (isset($contact->birthday_month)) {
                $usedColumns[] = Config::get('activities_columns.birthday_month');
            }
            if (isset($contact->anniversary)) {
                $usedColumns[] = Config::get('activities_columns.anniversary');
            }
            if (isset($contact->job_title)) {
                $usedColumns[] = Config::get('activities_columns.job_title');
            }
            if (isset($contact->company_name)) {
                $usedColumns[] = Config::get('activities_columns.company_name');
            }
            if (isset($contact->work_phone)) {
                $usedColumns[] = Config::get('activities_columns.work_phone');
            }
            if (isset($contact->home_phone)) {
                $usedColumns[] = Config::get('activities_columns.home_phone');
            }
			
            if (isset($contact->birthday_day)) {
                $usedColumns[] = Config::get('activities_columns.birthday_day');
            }
            if (isset($contact->birthday_month)) {
                $usedColumns[] = Config::get('activities_columns.birthday_month');
            }
            if (isset($contact->anniversary)) {
                $usedColumns[] = Config::get('activities_columns.anniversary');
            }

            // Addresses
            if (!empty($contact->addresses)) {
                $address = $contact->addresses[0];
                if (isset($address->line1)) {
                    $usedColumns[] = Config::get('activities_columns.address1');
                }
                if (isset($address->line2)) {
                    $usedColumns[] = Config::get('activities_columns.address2');
                }
                if (isset($address->line3)) {
                    $usedColumns[] = Config::get('activities_columns.address3');
                }
                if (isset($address->city)) {
                    $usedColumns[] = Config::get('activities_columns.city');
                }
                if (isset($address->state_code)) {
                    $usedColumns[] = Config::get('activities_columns.state');
                }
                if (isset($address->state_province)) {
                    $usedColumns[] = Config::get('activities_columns.state_province');
                }
                if (isset($address->country)) {
                    $usedColumns[] = Config::get('activities_columns.country');
                }
                if (isset($address->postal_code)) {
                    $usedColumns[] = Config::get('activities_columns.postal_code');
                }
                if (isset($address->sub_postal_code)) {
                    $usedColumns[] = Config::get('activities_columns.sub_postal_code');
                }
            }

            // Custom Fields
            if (!empty($contact->custom_fields)) {
                foreach ($contact->custom_fields as $customField) {
                    if (strpos($customField->name, 'custom_field_') !== false) {
                        $customFieldNumber = substr($customField->name, 13);
                        $usedColumns[] = Config::get('activities_columns.custom_field_' . $customFieldNumber);
                    }
                }
            }
            $this->column_names = $usedColumns;
        } else {
            $this->column_names = $columnNames;
        }
    }

    /**
     * Turn the object into json, removing any extra fields
     * @return string
     */
    public function toJson()
    {
        foreach ($this->import_data as $contact) {
            foreach ($contact as $key => $value) {
                if ($value == null) {
                    unset($contact->$key);
                }
            }
        }
        return json_encode($this);
    }
}
