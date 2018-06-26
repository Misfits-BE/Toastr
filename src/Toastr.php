<?php 

namespace ActivismeBe\Toastr; 

use Illuminate\Config\Repository; 
use Illuminate\Session\SessionManager;

/**
 * Class Toastr 
 * ----
 * Class for all the package functionality that needs to be provided 
 * for making the Toastr package work.
 * 
 * @author      Kamal Nasser <kamal@kamalnasser.net>
 * @copyright   2013 Kamal Nasser <kamal@kamalnasser.net>
 * @package     ActivismeBe\Toastr
 */
class Toastr 
{
    /**
     * Added notifications 
     * 
     * @var array $notifications
     */
    protected $notifications = [];

    /**
     * Illuminate Session 
     * 
     * @var \Illuminate\Session\SessionManager $session
     */
    protected $session;

    /**
     * Toastr config 
     * 
     * @var \Illuminate\Config\Repository $config
     */
    protected $config;

    /**
     * Toastr constructor 
     * 
     * @param  \Illuminate\Session\SessionManager       $session
     * @param  Repository|\Illuminate\Config\Repository $config
     * @return void 
     */
    public function __construct(SessionManager $session, Repository $config) 
    {
        $this->session = $session; 
        $this->config  = $config;
    }

    /**
     * Render the notifications script tag 
     * 
     * @return string
     */
    public function render(): string 
    {
        $notifications = $this->session->get('toastr::notifications'); 
        $output        = '<script type="text/javascript">';
        $lastconfig    = [];
        
        if (! $notifications) { // Notifications are empty so register a empty array under the variable.
            $notifications = [];
        }

        foreach ($notifications as $notification) {
            $config = $this->config->get('toastr.options');

            if (empty($config)) { // Config is emtpy so register a empty array under the variable. 
                $config = [];
            }

            if (count($notification['options']) > 0) { // Merge user supplied options with default options
                $config = array_merge($config, $notification['options']);
            }

            if ($config != $lastConfig) { // Config persists between toasts
                $output .= 'toastr.options = ' . json_encode($config) . ';';   
                $lastConfig = $config;
            }

            // Toastr output
            $output .= 'toastr.' . $notification['type'] . 
                "('" .  str_replace("'", "\\'", $notification['message']). "'" . 
                (isset($notification['title']) ? ", '" . str_replace("'", "\\'", htmlentities($notification['title'])) . "'" : null) . ');';
        }

        $output .= '</script>';

        return $output;
    }

    /**
     * Add a notification
     *
     * @param  string $type     Could be error, info, success or warning.
     * @param  string $message  The notification's message
     * @param  string $title    Title notification's title
     * @param  array  $options  User defined Toastr notification options.
     * @return bool|void        Return whether the notification was successfully added or not. 
     */
    public function add(string $type, string $message, ?string $title = null, array $options = [])
    {
        $allowedTypes = ['error', 'info', 'success', 'warning']; 

        if (! in_array($type, $allowedTypes)) {
            return false;
        }

        $this->notifications[] = ['type' => $type,'title' => $title, 'message' => $message, 'options' => $options];
        $this->session->flash('toastr::notifications', $this->notifications);
    }

    /**
     * Shortcut function for adding an info notification. 
     * 
     * @param  string $message  The notifications message
     * @param  string $title    The notifications title
     * @param  array  $options  User defined Toastr notification options
     * @return bool|void        Return whether the notifications are successfully created or not.
     */
    public function info(string $message, ?string $title = null, array $options = [])
    {
        $this->add('info', $message, $title, $options);
    }

    /**
     * Shortcut function for adding an error notification.
     * 
     * @param  string $message  The notifications message.
     * @param  string $title    The notifications title.
     * @param  array  $options  User defined Toastr notification options
     * @return bool|void        Return whether the notifications are successfully created or not.
     */
    public function error(string $message, ?string $title = null, array $options = []): void 
    {
        $this->add('error', $message, $title, $options);
    }

    /**
     * Shortcut function for adding an success notification.
     * 
     * @param  string $message   The notifications message. 
     * @param  string $title     The notifications title.
     * @param  string $options   User defined Toastr notification options 
     * @return bool|void         Return whether the notification are successfully created or not.
     */
    public function success(string $message, ?string $title = null, array $options = []): void 
    {
        $this->add('success', $message, $title, $options);
    }

    /**
     * Shortcut function for adding an warning notification.
     * 
     * @param  string $message  The notification message.
     * @param  string $title    The notification title.
     * @param  string $options  User defined Toastr notification options 
     * @return bool|void        Return whether the notification are successfully created or not. 
     */
    public function warning(string $message, ?string $title = null, array $options = []): void 
    {
        $this->add('warning', $message, $title, $options); 
    }

    /**
     * Clear all notifications
     *
     * @return void
     */
    public function clear() 
    {
        $this->notifications = [];
    }
}
