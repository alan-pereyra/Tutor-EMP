<?php
/**
 * Template for displaying instructors earnings
 *
 * @since v.1.1.2
 *
 * @author Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

global $wpdb;

$user_id = get_current_user_id();

/**
 * Getting the Last Month
 */
$year = date('Y', strtotime('-1 year'));
$dataFor = 'yearly';

$earning_sum = tutor_utils()->get_earning_sum($user_id, compact('year', 'dataFor'));

if ( ! $earning_sum){
	echo '<p>'.__('No hay información de ganancias disponible', 'tutor' ).'</p>';
	return;
}

$complete_status = tutor_utils()->get_earnings_completed_statuses();
$statuses = $complete_status;
$complete_status = "'".implode("','", $complete_status)."'";


/**
 * Query This Month
 */

$salesQuery = $wpdb->get_results( "
              SELECT SUM(instructor_amount) as total_earning,
              MONTHNAME(created_at)  as month_name
              from {$wpdb->prefix}tutor_earnings
              WHERE user_id = {$user_id} AND order_status IN({$complete_status})
              AND YEAR(created_at) = {$year}
              GROUP BY MONTH (created_at)
              ORDER BY MONTH(created_at) ASC ;");

$total_earning = wp_list_pluck($salesQuery, 'total_earning');
$months = wp_list_pluck($salesQuery, 'month_name');
$monthWiseSales = array_combine($months, $total_earning);

/**
 * Format yearly
 */
$emptyMonths = array();
for ($m=1; $m<=12; $m++) {
	$emptyMonths[date('F', mktime(0,0,0,$m, 1, date('Y')))] = 0;
}
$chartData = array_merge($emptyMonths, $monthWiseSales);

$statements = tutor_utils()->get_earning_statements($user_id, compact('year', 'dataFor', 'statuses'));

?>

    <div class="tutor-dashboard-info-cards">
        <div class="tutor-dashboard-info-card" title="<?php _e('Todo el tiempo', 'tutor'); ?>">
            <p>
                <span> <?php _e('Mis ganancias', 'tutor'); ?> </span>
                <span class="tutor-dashboard-info-val"><?php echo tutor_utils()->tutor_price($earning_sum->instructor_amount); ?></span>
            </p>
        </div>
        <div class="tutor-dashboard-info-card" title="<?php _e('Basado en el precio de los cursos', 'tutor'); ?>">
            <p>
                <span> <?php _e('Todas as ventas', 'tutor'); ?> </span>
                <span class="tutor-dashboard-info-val"><?php echo tutor_utils()->tutor_price($earning_sum->course_price_total); ?></span>
            </p>
        </div>
        <div class="tutor-dashboard-info-card">
            <p>
                <span> <?php _e('Comisiones deducidas', 'tutor'); ?> </span>
                <span class="tutor-dashboard-info-val"><?php echo tutor_utils()->tutor_price($earning_sum->admin_amount); ?></span>
            </p>
        </div>


        <?php if ($earning_sum->deduct_fees_amount > 0){ ?>
            <div class="tutor-dashboard-info-card" title="<?php _e('Matricula deducida', 'tutor'); ?>">
                <p>
                    <span> <?php _e('Matricula deducida', 'tutor'); ?> </span>
                    <span class="tutor-dashboard-info-val"><?php echo tutor_utils()->tutor_price($earning_sum->deduct_fees_amount); ?></span>
                </p>
            </div>
        <?php } ?>
    </div>





<div class="tutor-dashboard-item-group">
    <h4><?php echo sprintf(__("Datos de ganancias del año %s", 'tutor'), $year);?></h4>
    <?php
    tutor_load_template('dashboard.earning.chart-body', compact('chartData', 'statements'));
    ?>
</div>

<div class="tutor-dashboard-item-group">
    <h4><?php _e('Ventas en este periodo', 'tutor') ?></h4>
    <?php tutor_load_template('dashboard.earning.statement', compact('chartData', 'statements')); ?>
</div>
