<?php namespace ERegnier\NewsjetPlugin;
 /*
 Newsjet Plugin

 * @category Octobercms_Plugin
 * @author   Erwan Regnier <dev@eregnier.it>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://eregnier.it
 */
use System\Classes\PluginBase;
use System\Classes\SettingsManager;

class Plugin extends PluginBase
{

    public function boot()
    {
       
    }

    public function registerComponents()
    {
        return [
            'ERegnier\NewsjetPlugin\Components\SubscribeForm' => 'subscribeForm',
            'ERegnier\NewsjetPlugin\Components\DoubleOptin' => 'doubleOptin',
        ];
    }

    public function registerNavigation()
    {
        return [];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label' => 'Mailjet Config',
                'description' => 'Set api keys to use Mailjet',
                'icon' => 'icon-pencil-square-o',
                'class' => 'ERegnier\NewsjetPlugin\Models\Settings',
                'category' => SettingsManager::CATEGORY_MAIL
            ]
        ];
    }

}