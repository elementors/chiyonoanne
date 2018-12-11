<?php
defined( 'ABSPATH' ) || die( 'Cheatin’ uh?' );

/**
 * Imagify User class.
 *
 * @since 1.0
 */
class Imagify_User {

	/**
	 * Class version.
	 *
	 * @var string
	 */
	const VERSION = '1.0.1';

	/**
	 * The Imagify user ID.
	 *
	 * @since 1.0
	 *
	 * @var    string
	 * @access public
	 */
	public $id;

	/**
	 * The user email.
	 *
	 * @since 1.0
	 *
	 * @var    string
	 * @access public
	 */
	public $email;

	/**
	 * The plan ID.
	 *
	 * @since 1.0
	 *
	 * @var    int
	 * @access public
	 */
	public $plan_id;

	/**
	 * The plan label.
	 *
	 * @since 1.2
	 *
	 * @var    string
	 * @access public
	 */
	public $plan_label;

	/**
	 * The total quota.
	 *
	 * @since 1.0
	 *
	 * @var    int
	 * @access public
	 */
	public $quota;

	/**
	 * The total extra quota (Imagify Pack).
	 *
	 * @since 1.0
	 *
	 * @var    int
	 * @access public
	 */
	public $extra_quota;

	/**
	 * The extra quota consumed.
	 *
	 * @since 1.0
	 *
	 * @var    int
	 * @access public
	 */
	public $extra_quota_consumed;

	/**
	 * The current month consumed quota.
	 *
	 * @since 1.0
	 *
	 * @var    int
	 * @access public
	 */
	public $consumed_current_month_quota;

	/**
	 * The next month date to credit the account.
	 *
	 * @since 1.1.1
	 *
	 * @var    date
	 * @access public
	 */
	public $next_date_update;

	/**
	 * If the account is activate or not.
	 *
	 * @since 1.0.1
	 *
	 * @var    bool
	 * @access public
	 */
	public $is_active;

	/**
	 * The constructor.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function __construct() {
		$user = get_imagify_user();

		if ( is_wp_error( $user ) ) {
			return;
		}

		$this->id                           = $user->id;
		$this->email                        = $user->email;
		$this->plan_id                      = (int) $user->plan_id;
		$this->plan_label                   = ucfirst( $user->plan_label );
		$this->quota                        = $user->quota;
		$this->extra_quota                  = $user->extra_quota;
		$this->extra_quota_consumed         = $user->extra_quota_consumed;
		$this->consumed_current_month_quota = $user->consumed_current_month_quota;
		$this->next_date_update             = $user->next_date_update;
		$this->is_active                    = $user->is_active;
	}

	/**
	 * Percentage of consumed quota, including extra quota.
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @return float|int
	 */
	public function get_percent_consumed_quota() {
		static $done = false;

		$quota          = $this->quota;
		$consumed_quota = $this->consumed_current_month_quota;

		if ( imagify_round_half_five( $this->extra_quota_consumed ) < $this->extra_quota ) {
			$quota          += $this->extra_quota;
			$consumed_quota += $this->extra_quota_consumed;
		}

		if ( ! $quota || ! $consumed_quota ) {
			$percent = 0;
		} else {
			$percent = 100 * $consumed_quota / $quota;
			$percent = round( $percent, 1 );
			$percent = min( max( 0, $percent ), 100 );
		}

		if ( ! $done ) {
			$previous_percent = Imagify_Data::get_instance()->get( 'previous_quota_percent' );

			// Percent is not 100% anymore.
			if ( 100 === $previous_percent && $percent < 100 ) {
				/**
				 * Triggered when the consumed quota percent decreases below 100%.
				 *
				 * @since  1.7
				 * @author Grégory Viguier
				 *
				 * @param float|int $percent The current percentage of consumed quota.
				 */
				do_action( 'imagify_not_over_quota_anymore', $percent );
			}
			// Percent is not >= 80% anymore.
			if ( $previous_percent >= 80 && $percent < 80 ) {
				/**
				 * Triggered when the consumed quota percent decreases below 80%.
				 *
				 * @since  1.7
				 * @author Grégory Viguier
				 *
				 * @param float|int $percent          The current percentage of consumed quota.
				 * @param float|int $previous_percent The previous percentage of consumed quota.
				 */
				do_action( 'imagify_not_almost_over_quota_anymore', $percent, $previous_percent );
			}

			if ( $previous_percent !== $percent ) {
				Imagify_Data::get_instance()->set( 'previous_quota_percent', $percent );
			}

			$done = true;
		}

		return $percent;
	}

	/**
	 * Count percent of unconsumed quota.
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @return float|int
	 */
	public function get_percent_unconsumed_quota() {
		$percent = 100 - $this->get_percent_consumed_quota();
		return $percent;
	}

	/**
	 * Check if the user has a free account.
	 *
	 * @since 1.1.1
	 *
	 * @access public
	 * @return bool
	 */
	public function is_free() {
		if ( 1 === $this->plan_id ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if the user has consumed all his/her quota.
	 *
	 * @since 1.1.1
	 *
	 * @access public
	 * @return bool
	 */
	public function is_over_quota() {
		if ( empty( $this->id ) ) {
			return true;
		}

		if ( $this->is_free() && 100 === $this->get_percent_consumed_quota() ) {
			return true;
		}

		return false;
	}
}