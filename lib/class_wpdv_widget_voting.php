<?php
/**
 * Shows "Vote" box with number of votes.
 */
class Wdpv_WidgetVoting extends WP_Widget {

	//function Wdpv_WidgetVoting () {
	function __construct () {
		$widget_ops = array('classname' => __CLASS__, 'description' => __('Zeigt das Feld "Abstimmung" für den aktuellen Beitrag/die aktuelle Seite mit der Anzahl der Stimmen an.', 'wdpv'));
		parent::__construct(__CLASS__, __('Voting Widget', 'wdpv'), $widget_ops);
	}

	function form($instance) {
        $defaults = array(
            'title' => '',
            'show_vote_up' => false,
            'show_vote_down' => false,
            'show_vote_result' => false
        );

        $instance = wp_parse_args( $instance, $defaults );

		$title = esc_attr($instance['title']);
		$show_vote_up = esc_attr($instance['show_vote_up']);
		$show_vote_down = esc_attr($instance['show_vote_down']);
		$show_vote_result = esc_attr($instance['show_vote_result']);

		// Set defaults
		// ...

		$html = '<p>';
		$html .= '<label for="' . $this->get_field_id('title') . '">' . __('Titel:', 'wdpv') . '</label>';
		$html .= '<input type="text" name="' . $this->get_field_name('title') . '" id="' . $this->get_field_id('title') . '" class="widefat" value="' . $title . '"/>';
		$html .= '</p>';

		$html .= '<p>';
		$html .= '<label for="' . $this->get_field_id('show_vote_up') . '">' . __('Schaltfläche "Positiv abstimmen" anzeigen:', 'wdpv') . '</label>';
		$html .= '<input type="checkbox" name="' . $this->get_field_name('show_vote_up') . '" id="' . $this->get_field_id('show_vote_up') . '" value="1" ' . ($show_vote_up ? 'checked="checked"' : '') . ' />';
		$html .= '</p>';

		$html .= '<p>';
		$html .= '<label for="' . $this->get_field_id('show_vote_down') . '">' . __('Schaltfläche "Negativ abstimmen" anzeigen:', 'wdpv') . '</label>';
		$html .= '<input type="checkbox" name="' . $this->get_field_name('show_vote_down') . '" id="' . $this->get_field_id('show_vote_down') . '" value="1" ' . ($show_vote_down ? 'checked="checked"' : '') . ' />';
		$html .= '</p>';

		$html .= '<p>';
		$html .= '<label for="' . $this->get_field_id('show_vote_result') . '">' . __('Abstimmungsergebnisse anzeigen:', 'wdpv') . '</label>';
		$html .= '<input type="checkbox" name="' . $this->get_field_name('show_vote_result') . '" id="' . $this->get_field_id('show_vote_result') . '" value="1" ' . ($show_vote_result ? 'checked="checked"' : '') . ' />';
		$html .= '</p>';

		echo $html;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['show_vote_up'] = strip_tags($new_instance['show_vote_up']);
		$instance['show_vote_down'] = strip_tags($new_instance['show_vote_down']);
		$instance['show_vote_result'] = strip_tags($new_instance['show_vote_result']);

		return $instance;
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$show_vote_up = (int)@$instance['show_vote_up'];
		$show_vote_up = $show_vote_up ? true : false;

		$show_vote_down = (int)@$instance['show_vote_down'];
		$show_vote_down = $show_vote_down ? true : false;

		$show_vote_down = (int)@$instance['show_vote_down'];
		$show_vote_down = $show_vote_down ? true : false;

		$show_vote_result = (int)@$instance['show_vote_result'];
		$show_vote_result = $show_vote_result ? true : false;

		$show_entire_widget = ($show_vote_up && $show_vote_down && $show_vote_result);

		if (is_singular()) { // Show widget only on votable pages
			$codec = new Wdpv_Codec;
			echo $before_widget;
			if ($title) echo $before_title . $title . $after_title;

			if ($show_entire_widget) {
				echo $codec->process_vote_widget_code(array());
			} else {
				if ($show_vote_up) echo $codec->process_vote_up_code(array('standalone'=>'no'));
				if ($show_vote_result) echo $codec->process_vote_result_code(array('standalone'=>'no'));
				if ($show_vote_down) echo $codec->process_vote_down_code(array('standalone'=>'no'));
				echo "<div class='wdpv_clear'></div>";
			}

			echo $after_widget;
		}
	}
}