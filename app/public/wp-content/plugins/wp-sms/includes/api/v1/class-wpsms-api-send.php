<?php

namespace WP_SMS\Api\V1;

use Exception;
use WP_REST_Request;
use DateTime;
use DateInterval;
use WP_SMS\Gateway;
use WP_SMS\Helper;
use WP_SMS\Newsletter;
use WP_SMS\Pro\Scheduled;
use WP_SMS\Pro\RepeatingMessages;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * @category   class
 * @package    WP_SMS_Api
 * @version    1.0
 */
class SendSmsApi extends \WP_SMS\RestApi
{
    private $sendSmsArguments = [
        'sender'     => array('required' => true, 'type' => 'string'),
        'recipients' => array('required' => true, 'type' => 'string', 'enum' => ['subscribers', 'users', 'wc-customers', 'bp-users', 'numbers']),
        'group_ids'  => array('required' => false, 'type' => 'array'),
        'role_ids'   => array('required' => false, 'type' => 'array'),
        'numbers'    => array('required' => false, 'type' => 'array', 'format' => 'uri'),
        'message'    => array('required' => true, 'type' => 'string'),
        'flash'      => array('required' => false, 'type' => 'boolean'),
        'media_urls' => array('required' => false, 'type' => 'array'),
        'schedule'   => array('required' => false, 'type' => 'string', 'format' => 'date-time'),
        'repeat'     => array('required' => false, 'type' => 'object')
    ];

    public function __construct()
    {
        // Register routes
        add_action('rest_api_init', array($this, 'register_routes'));

        parent::__construct();
    }

    /**
     * Register routes
     */
    public function register_routes()
    {
        register_rest_route($this->namespace . '/v1', '/send', array(
            array(
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'sendSmsCallback'),
                'args'                => $this->sendSmsArguments,
                'permission_callback' => array($this, 'sendSmsPermission'),
            )
        ));

        // @todo, this can be moved to a separate class
        register_rest_route($this->namespace . '/v1', '/outbox', array(
            array(
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => array($this, 'getOutboxCallback'),
                'permission_callback' => function () {
                    return current_user_can('wpsms_outbox');
                },
            )
        ));
    }

    /**
     * @param WP_REST_Request $request
     *
     * @return \WP_REST_Response
     */
    public function sendSmsCallback(WP_REST_Request $request)
    {
        try {
            $recipientNumbers = $this->getRecipientsFromRequest($request);
            $mediaUrls        = array_filter($request->get_param('media_urls'));

            if (count($recipientNumbers) === 0) {
                throw new Exception(__('Could not find any mobile numbers.', 'wp-sms'));
            }

            if (!$request->get_param('message')) {
                throw new Exception(__('The message body can not be empty.', 'wp-sms'));
            }

            /**
             * Make shorter the URLs in the message
             */
            $message = Helper::makeUrlsShorter($request->get_param('message'));

            /*
             * Repeating SMS
             */
            if ($request->has_param('schedule') && $request->has_param('repeat')) {
                $data      = $request->get_param('repeat');
                $startDate = new DateTime(get_gmt_from_date($request->get_param('schedule')));
                $endDate   = isset($data['endDate']) ? (new DateTime(get_gmt_from_date($data['endDate']))) : null;
                $interval  = $data['interval'];

                if ($startDate->getTimestamp() < time()) {
                    return self::response(__('Selected start date must be in future', 'wp-sms'), 400);
                }

                if (isset($endDate) && $endDate->getTimestamp() < $startDate->getTimestamp()) {
                    return self::response(__('Selected end date must be after start date', 'wp-sms'), 400);
                }

                RepeatingMessages::add(
                    $startDate,
                    $endDate,
                    $interval['value'],
                    $interval['unit'],
                    $request->get_param('sender'),
                    $message,
                    $recipientNumbers,
                    $mediaUrls
                );
                return self::response(__('Repeating SMS is scheduled successfully!', 'wp-sms'));
            }

            /**
             * Scheduled SMS
             */
            if ($request->has_param('schedule')) {
                if ((new DateTime(get_gmt_from_date($request->get_param('schedule'))))->getTimestamp() < time()) {
                    return self::response(__('Selected start date must be in future', 'wp-sms'), 400);
                }

                Scheduled::add(
                    $request->get_param('schedule'),
                    $request->get_param('sender'),
                    $message,
                    $recipientNumbers,
                    true,
                    $mediaUrls
                );
                return self::response('SMS Scheduled Successfully!');
            }

            /*
             * Regular SMS
             */
            $response = wp_sms_send(
                $recipientNumbers,
                $message,
                $request->get_param('flash'),
                $request->get_param('sender'),
                $mediaUrls
            );

            if (is_wp_error($response)) {
                throw new Exception($response->get_error_message());
            }

            return self::response(__('Successfully send SMS!', 'wp-sms'), 200, [
                'balance' => Gateway::credit()
            ]);
        } catch (\Throwable $e) {
            return self::response($e->getMessage(), 400);
        }
    }

    /**
     * @throws Exception
     */
    private function getRecipientsFromRequest(WP_REST_Request $request)
    {
        $recipients = [];

        switch ($request->get_param('recipients')) {
            /**
             * Subscribers
             */
            case 'subscribers':

                $group_ids = $request->get_param('group_ids');
                $groups    = Newsletter::getGroups();

                // Check there is group or not
                if ($groups) {
                    if (!$request->get_param('group_ids')) {
                        throw new Exception(__('Parameter group_ids is required', 'wp-sms'));
                    }

                    // Check group validity
                    foreach ($group_ids as $group_id) {
                        if (!Newsletter::getGroup($group_id)) {
                            $group_validity_error[] = sprintf(__('The group ID %s is not valid', 'wp-sms'), $group_id);
                        }
                    }

                    if (isset($group_validity_error) && !empty($group_validity_error)) {
                        throw new Exception($group_validity_error);
                    }
                }

                $recipients = Newsletter::getSubscribers($group_ids, true);
                break;

            /**
             * Users
             */
            case 'users':

                if (!$request->get_param('role_ids')) {
                    throw new Exception(__('Parameter role_ids is required', 'wp-sms'));
                }

                $recipients = Helper::getUsersMobileNumbers($request->get_param('role_ids'));
                break;

            /**
             * WooCommerce customers
             */
            case 'wc-customers':

                if (class_exists('woocommerce') and class_exists('WP_SMS\Pro\WooCommerce\Helper')) {
                    $recipients = \WP_SMS\Pro\WooCommerce\Helper::getCustomersNumbers();
                } else {
                    throw new Exception(__('WooCommerce or WP-SMS Pro is not enabled', 'wp-sms-pro'));
                }

                break;

            /**
             * BuddyPress users
             */
            case 'bp-users':

                if (class_exists('BuddyPress') and class_exists('WP_SMS\Pro\BuddyPress')) {
                    $recipients = \WP_SMS\Pro\BuddyPress::getTotalMobileNumbers();
                } else {
                    throw new Exception(__('BuddyPress or WP SMS Pro is not enabled', 'wp-sms-pro'));
                }

                break;

            /**
             * Numbers
             */
            case 'numbers':

                if (!$request->get_param('numbers')) {
                    throw new Exception(__('Parameter numbers is required', 'wp-sms'));
                }

                $recipients = $request->get_param('numbers');
                break;
        }

        return apply_filters('wp_sms_api_recipients_numbers', $recipients, $request->get_param('recipients'));
    }

    /**
     * @param WP_REST_Request $request
     * @return array|object|\stdClass[]|null
     * @todo support pagination and filter
     */
    public function getOutboxCallback(WP_REST_Request $request)
    {
        $query = "SELECT * FROM `{$this->tb_prefix}sms_send`";

        return $this->db->get_results($query, ARRAY_A);
    }

    /**
     * Check user permission
     *
     * @param $request
     *
     * @return bool
     */
    public function sendSmsPermission($request)
    {
        return current_user_can('wpsms_sendsms');
    }
}

new SendSmsApi();
