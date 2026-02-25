<?php

/**
 * Handles all hubwoo schedulers.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    makewebbetter-hubspot-for-woocommerce
 * @subpackage makewebbetter-hubspot-for-woocommerce/includes
 */

/**
 * Handles all hubwoo schedulers.
 * Provide a list of functions to manage schedulers.
 *
 * @package    makewebbetter-hubspot-for-woocommerce
 * @subpackage makewebbetter-hubspot-for-woocommerce/includes
 */
class HubWoo_Schedulers
{
    /**
     * The single instance of the class.
     *
     * @since   1.6.5
     * @var HubWoo_Schedulers  The single instance of the HubWoo_Schedulers
     */
    protected static $instance = null;
    /**
     * Main HubWoo_Schedulers Instance.
     *
     * Ensures only one instance of HubWoo_Schedulers is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @return HubWoo_Schedulers - Main instance.
     */
    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function hubwoo_initate_schedulers()
    {
        if (! as_next_scheduled_action('hubwoo_real_time_sync')) {
            as_schedule_recurring_action(time(), 300, 'hubwoo_real_time_sync');
        }
        if (! as_next_scheduled_action('hubwoo_abncart_clear_old_cart')) {
            as_schedule_recurring_action(time() + 120, 86400, 'hubwoo_abncart_clear_old_cart');
        }
        if (! as_next_scheduled_action('hubwoo_check_logs')) {
            as_schedule_recurring_action(time() + 240, 86400, 'hubwoo_check_logs');
        }
        if (! as_next_scheduled_action('hubwoo_check_action_schedulers_logs')) {
            as_schedule_recurring_action(time() + 360, 86400, 'hubwoo_check_action_schedulers_logs');
        }
    }

    public function hubwoo_trigger_heartbeat()
    {
        if (as_has_scheduled_action('hubwoo_real_time_sync')) {
            return;
        }
        as_schedule_recurring_action(time(), 300, 'hubwoo_real_time_sync');
    }

    public function is_task_running($task)
    {
        $lock_time = get_option($this->lock_key($task));
        if (! $lock_time) {
            return false;
        }

        // 🔥 Self-healing: release stuck locks after 15 minutes
        if ((time() - $lock_time) > 900) {
            delete_option($this->lock_key($task));
            return false;
        }

        return true;
    }

    public function should_schedule_task($task)
    {
        $requires_setup = array('contacts_sync', 'products_sync');
        if (in_array($task, $requires_setup, true)) {
            return Hubwoo::is_setup_completed();
        }

        $deal_tasks = array('deals_sync', 'deals_update');
        if (in_array($task, $deal_tasks, true)) {
            return 'yes' === get_option('hubwoo_ecomm_deal_enable', 'yes');
        }

        return true;
    }

    public function acquire_lock($task)
    {
        update_option($this->lock_key($task), time(), false);
    }

    public function release_lock($task)
    {
        delete_option($this->lock_key($task));
    }

    private function lock_key($task)
    {
        return "hubwoo_lock_{$task}";
    }
}

// new HubWoo_Enterprise_Scheduler();
