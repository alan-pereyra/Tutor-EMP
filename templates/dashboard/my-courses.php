<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

?>

<h3><?php _e('Mis cursos', 'tutor'); ?></h3>

<div class="tutor-dashboard-content-inner">

	<?php
	$my_courses = tutor_utils()->get_courses_by_instructor(null, array('publish', 'draft', 'pending'));

	if (is_array($my_courses) && count($my_courses)):
		global $post;
		foreach ($my_courses as $post):
			setup_postdata($post);

			$avg_rating = tutor_utils()->get_course_rating()->rating_avg;
            $tutor_course_img = get_tutor_course_thumbnail_src();
			?>

            <a href="<?php echo get_the_permalink(); ?>" class="tutor-course-a"><div id="tutor-dashboard-course-<?php the_ID(); ?>" class="tutor-mycourse-wrap tutor-mycourse-<?php the_ID(); ?>">
                <div class="tutor-mycourse-thumbnail" style="background-image: url(<?php echo esc_url($tutor_course_img); ?>)"></div></a>
                <div class="tutor-mycourse-content">
                    <div class="tutor-mycourse-rating">
						<?php
						tutor_utils()->star_rating_generator($avg_rating);
						?>
                    </div>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> </h3>
                    <div class="tutor-meta tutor-course-metadata">
						<?php
                            $total_lessons = tutor_utils()->get_lesson_count_by_course();
                            $completed_lessons = tutor_utils()->get_completed_lesson_count_by_course();

                            $course_duration = get_tutor_course_duration_context();
                            $course_students = tutor_utils()->count_enrolled_users_by_course();
						?>
                        <ul>
                            <li>
								<?php
								_e('Estado:', 'tutor');
								$status = ucwords($post->post_status);
								echo "<span>$status</span>";
								?>
                            </li>
                            <li>
								<?php
								_e('Duración:', 'tutor');
								echo "<span>$course_duration</span>";
								?>
                            </li>
                            <li>
								<?php
								_e('Estudiantes:', 'tutor');
								echo "<span>$course_students</span>";
								?>
                            </li>
                        </ul>
                    </div>

                    <div class="mycourse-footer">
                        <div class="tutor-mycourses-stats">
	                        <?php echo tutor_utils()->tutor_price(tutor_utils()->get_course_price()); ?>
                            <a href="<?php echo tutor_utils()->course_edit_link($post->ID); ?>" class="tutor-mycourse-edit"> <i
                                        class="tutor-icon-pencil"></i>
                                Edit</a>
                            <a href="#tutor-course-delete" class="tutor-mycourse-delete-btn" data-course-id="<?php echo $post->ID; ?>">
                                <i class="tutor-icon-garbage"></i> Eliminar
                            </a>
                        </div>
                    </div>
                </div>

            </div>
		<?php
		endforeach;
	else : ?>
        <div>
            <h2><?php _e("No encontrado" , 'tutor'); ?></h2>
            <p><?php _e("Lo sentimos, pero estás buscando algo que no está aquí." , 'tutor'); ?></p>
        </div>
	<?php endif; ?>


    <div class="tutor-frontend-modal" data-popup-rel="#tutor-course-delete" style="display: none">
        <div class="tutor-frontend-modal-overlay"></div>
        <div class="tutor-frontend-modal-content">
            <button class="tm-close tutor-icon-line-cross"></button>

            <div class="tutor-modal-body tutor-course-delete-popup">
                <img src="<?php echo tutor()->url . 'assets/images/delete-icon.png' ?>" alt="">
                <h3><?php _e('Eliminar este curso?', 'tutor'); ?></h3>
                <p><?php _e("Vas a eliminar este curso, no se puede deshacer", 'tutor'); ?></p>
                <div class="tutor-modal-button-group">
                    <form action="" id="tutor-delete-course-form">
                        <input type="hidden" name="action" value="tutor_delete_dashboard_course">
                        <input type="hidden" name="course_id" id="tutor-course-delete-id" value="">
                        <button type="button" class="tutor-modal-btn-cancel"><?php _e('Cancelar', 'tutor') ?></button>
                        <button type="submit" class="tutor-danger tutor-modal-course-delete-btn"><?php _e('Si, eliminar este curso', 'tutor') ?></button>
                    </form>
                </div>
            </div>

        </div> <!-- tutor-frontend-modal-content -->
    </div> <!-- tutor-frontend-modal -->

</div>
