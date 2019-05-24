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


class DoubleOptin extends ComponentBase
{
	private $status;
	private $api_key;
	private $api_secret;

	public function defineProperties()
    {
        return [
          'list_id' => [
            'title' => 'Mailjet list ID',
            'description' => 'Mailjet list ID to subscribe',
            'type' => 'string',
            'validationPattern' => '^[0-9]+$',
            'required' => true,
            'default' => ''
          ]
        ];
    }

	public function onRun()
	{
		$receivedHash = $this->param('vkey');
		$email = $this->param('umail');

		$this->status = Settings::instance()->validate_error?: 'Wrong token';

		if ($this->validateHash($email, $receivedHash)) {

			$this->api_key = Settings::instance()->api_key?: false;
			$this->api_secret = Settings::instance()->api_secret?: false;

			if ($this->addMailjetContact($email)) {
				$this->status = Settings::instance()->optin_confirm?: 'You successfuly subscribed, thank you!';
			} else {
				$this->status = Settings::instance()->optin_error?: 'Cannot subscribe email to the list. Please try again later';
			}

		}
	}

	public function onRender()
	{
		$this->page['status'] = $this->status;
	}

	public function componentDetails()
    {
        return [
            'name'        => 'doupleOptin',
            'description' => 'Double Optin verification component'
        ];
    }

    protected function validateHash($email, $hash)
    {
    	if (hash('crc32b', $email) === $hash)
    		return true;
    	else
    		return false;
    }

    private function addMailjetContact($email)
    {

    	$mj = new Mailjet($this->api_key, $this->api_secret);
    	$body= ['Email' => $email, 'Action' => 'addforce'];

        $response = $mj->post(Resources::$ContactslistManagecontact, ['id' => $this->property('list_id'), 'body' => $body]);
        if ($response->getStatus() == 201)
        	return true;
        else
        	return false;
        
    }

}