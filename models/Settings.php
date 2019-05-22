<?php namespace ERegnier\NewsjetPlugin\Models;
 /*
 Newsjet Plugin

 * @category Octobercms_Plugin
 * @author   Erwan Regnier <dev@eregnier.it>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://eregnier.it
 */
use Model;
use Validator;
use ValidationException;
use \October\Rain\Database\Traits\Validation;

class Settings extends Model
{

    public $implement = [
    	'System.Behaviors.SettingsModel',
    	'@RainLab.Translate.Behaviors.TranslatableModel'
    ];

    public $rules = [
        'api_key' => ['required'],
        'api_secret' => ['required'],
        'template_id' => ['required', 'integer']
    ];

    // A unique code
    public $settingsCode = 'eregnier_newsjetplugin_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    public $translatable = [
      'email_text',
      'privacy_text',
      'submit_text',
      'consent_fields',
      'confirm_text',
      'template_id'
    ];
}