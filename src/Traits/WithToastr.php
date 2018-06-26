<?php 

namespace ActivismeBe\Toastr\Traits;

/**
 * Trait WithToastr 
 * -----
 * Trait for checking the toastr session's in phpunit functions. 
 * 
 * @author      Tim Joosten <tim@activisme.be>
 * @copyright   2018 Tim Joosten <MIT license>
 * @package     ActivismeBe\Toastr\Testing
 */
trait WithToastr 
{
    /**
     * Custom assertions for toastr flash messages. 
     * 
     * @param  string $type     The type of the toastr notification. 
     * @param  string $title    The title of the toastr notification.
     * @param  string $message  The actual toastr message. 
     * @param  array  $options  The user defined configuration for the toastr message.
     * @return void
     */
    protected function assertHasToastr(string $type, string $title, string $message, array $options = []): void
    {
        $expectedNotification = ['type' => $type, 'title' => $title, 'message' => $message, 'options' => $options];
        $flashNotifications   = json_decode(json_encode(session('toastr::notifications')), true);
        $assertMessage        = 'Failed asserting that the toastr message '. $message .' is present';

        if (! $flashNotifications) {
            $this->fail('Failed asserting that a flash message was sent.');
        }

        $this->assertContains($expectedNotification, $flashNotifications, $assertMessage);
    }
}