<?php namespace ERegnier\NewsjetPlugin\Components;
 /*
 Newsjet Plugin

 * @category Octobercms_Plugin
 * @author   Erwan Regnier <dev@eregnier.it>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://eregnier.it
 */
use Cms\Classes\ComponentBase;
use ERegnier\NewsjetPlugin\Models\Settings;
use ERegnier\NewsjetPlugin\Classes\Mailjet\Resources as Resources;
use ERegnier\NewsjetPlugin\Classes\Mailjet\Client as Mailjet;
use Cms\Classes\Page;
use Validator;
use ValidationException;
use October\Rain\Exception\AjaxException;

class SubscribeForm extends ComponentBase
{
    private $api_key;
    private $api_secret;

	public $rules = [
        'email' => ['required', 'email']
    ];

    public function defineProperties()
    {
        return [
            'doubleoptinPage' => [
                'title'       => 'Double optin page',
                'description' => 'The page containing the doubleOptin component to validate the confirmation link',
                'type'        => 'dropdown',
                'default'     => 'newsletter-optin',
                'required'     => true
            ]
        ];
    }

    public function getDoubleoptinPageOptions()
    {
        $pages = Page::sortBy('baseFileName');
        $pageList = [];
        foreach ($pages as $p) {
            if ($p->getComponent('doubleOptin')) {
                $pageList[$p->baseFileName] = $p->baseFileName;
            }
        }
        return $pageList;
    }

    public function componentDetails()
    {
        return [
            'name'        => 'subscribeForm',
            'description' => 'Subscribe to newsletter via Mailjet form component'
        ];
    }

    public function onMailSend()
    {
        $this->api_key = Settings::instance()->api_key?: false;
        $this->api_secret = Settings::instance()->api_secret?: false;
        $consent_fields = Settings::instance()->consent_fields ?: [];
        $count = 1;

        //Add the required consent fields to rules 
        foreach ($consent_fields as $cf) {
            if ($cf['consent_required']) {
                $this->rules['consent_field_' . $count] = ['required'];
            }
            ++$count;
        }

        $validator = Validator::make(post(), $this->rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        if ($this->sendConfirmEmail(post('email'))) {
            $confirmation_message = Settings::instance()->confirm_text?: 'Thank you! Please check your mailbox and click on the confirmation link.';
            $this->page['confirm_text'] = $confirmation_message;
        } else {
            throw new AjaxException(['.newsjet_subscribe_error' => $this->renderPartial('@error.htm')]);
        }

    }

    public function onRender()
    {
        $this->page['text'] = [
            'email' => Settings::instance()->email_text ?: "Email",
            'submit' => Settings::instance()->submit_text ?: "Send",
        ];

       $this->page['consent_fields'] = Settings::instance()->consent_fields ?: [];

        if (empty(Settings::instance()->api_key) || empty(Settings::instance()->api_secret)) {
            $this->page['mailjet_config'] = false;
        } else {
            $this->page['mailjet_config'] = true;
        }
    }

    protected function sendConfirmEmail($email)
    {
        $mj = new Mailjet($this->api_key, $this->api_secret, true, ['version' => 'v3.1']);

        $sender_name = Settings::instance()->sender_name?: false;
        $sender_email = Settings::instance()->sender_mail?: false;
        $template_id = Settings::instance()->template_id?: false;

        if (!empty($sender_email) && !empty($template_id)) {

            //Retrieve the confirmation link from the double optin selected page
            $confirm_link = $this->pageUrl(
              $this->property('doubleoptinPage'),
              ['vkey' => $this->emailHash($email), 'umail' => $email]
            );

            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => $sender_email,
                            'Name' => $sender_name
                        ],
                        'To' => [
                            [
                                'Email' => $email
                            ]
                        ],
                        'TemplateID' => (int)$template_id,
                        'TemplateLanguage' => true,
                        'Variables' => ['confirm_link' => $confirm_link]
                    ]
                ]
            ];

            $response = $mj->post(Resources::$Email, ['body' => $body]);

            if ($response->getStatus() == 200) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    protected function emailHash($email)
    {
        return hash('crc32b', $email);
    }
}