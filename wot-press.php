<?php

/**
Plugin Name: WOT Press
Description: Displays account data from Wargaming.net. Now is statistic from your profile in World of Tanks game available.
Version: 0.1
Author: Limeira Studio
Author URI: http://www.limeirastudio.com/
License: GPL2
Copyright: Limeira Studio
Text Domain: wot-press
Domain Path: /lang/
*/

function register_wot_widget()	{
	register_widget('WOT_Press');
}
add_action('widgets_init', 'register_wot_widget');

class WOT_Press extends WP_Widget {
		
	private $defaults;
	private $text_domain;
	
	function __construct()	{
		$options = array(
            'description'   =>  'Displays account data from Wargaming.net. Now is statistic from your profile in World of Tanks game available.',
            'name'          =>  'WOT Press'
        );
		
		parent::__construct('wot_press', '', $options);
		
		$this->defaults =  array(
		'title'				=> 'My Profile in WOT',
		'app_id'			=> '93cd23e8c8bd79abe8fb0901bf76150c',
		'profile_link'		=> 'on'
		);
		
		$this->text_domain = 'wot-press';
		add_action('init', array(&$this,'text_domain'));
	}
	
	public function form($instance)	{

		$instance = wp_parse_args((array)$instance, $this->defaults);
		$title = ! empty($instance['title']) ? $instance['title'] : '';
		$nickname = ! empty($instance['nickname']) ? $instance['nickname'] : '';
		$profile_link = ! empty($instance['profile_link']) ? $instance['profile_link'] : '';
		?>
		<p>
			<label for="<?=$this->get_field_id('title'); ?>"><?php _e('Title', $this->text_domain); ?></label> 
			<input class="widefat" id="<?=$this->get_field_id('title'); ?>" name="<?=$this->get_field_name('title'); ?>" type="text" value="<?=esc_attr($title); ?>">
		</p>
		<p>
			<label for="<?=$this->get_field_id('nickname'); ?>"><?php _e('Nickname', $this->text_domain); ?></label> 
			<input class="widefat" id="<?=$this->get_field_id('nickname'); ?>" name="<?=$this->get_field_name('nickname'); ?>" type="text" value="<?=esc_attr($nickname); ?>">
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($profile_link, 'on'); ?> id="<?=$this->get_field_id('profile_link'); ?>" name="<?=$this->get_field_name('profile_link'); ?>" /> 
		    <label for="<?=$this->get_field_id('profile_link'); ?>"> <?php _e('Profile Link', $this->text_domain); ?></label>
		</p>
			<?php 
	}
	
	public function widget($args, $instance)	{

		$title = $instance['title'];
		$nickname = $instance['nickname'];
		$profile_link = $instance['profile_link'];
		echo $args['before_widget'];
		if($title)	{
			echo '<h3 class="wot-press-widget-title">'.$title.'</h3>';
		}
		if(!$nickname)	{
			echo _e('Nickname not specified', $this->text_domain); return;
		}	else {
			if($profile_link)	{
				echo '<h4><a title="'.$title.'" target="_blank" href="http://worldoftanks.ru/community/accounts/'.$this->get_acc_id($nickname).'-'.$nickname.'/">'.$nickname.'</a></h4>';
			}	else {
				echo '<h4>'.$nickname.'</h4>';
			}

			$data = $this->get_stat($nickname, 'basic');
	
			echo '<span>'._e('Battles fought', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['all']['battles'].'<br/>';
			echo '<span>'._e('Personal rating', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['global_rating'].'<br/>';
			echo '<span>'._e('Hits', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['all']['hits'].'<br/>';
			echo '<span>'._e('Victories', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['all']['wins'].'<br/>';
			echo '<span>'._e('Defeats', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['all']['losses'].'<br/>';
			echo '<span>'._e('Draws', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['all']['draws'].'<br/>';
			echo '<span>'._e('Total experience', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['all']['xp'].'<br/>';
			echo '<span>'._e('Average experience per battle', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['all']['battle_avg_xp'].'<br/>';
			echo '<span>'._e('Maximum experience per battle', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['max_xp'].'<br/>';
			echo '<span>'._e('Vehicles destroyed', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['all']['frags'].'<br/>';
			echo '<span>'._e('Shots fired', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['all']['shots'].'<br/>';
			echo '<span>'._e('Maximum destroyed in battle', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['max_frags'].'<br/>';
			echo '<span>'._e('Maximum damage caused per battle', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['max_damage'].'<br/>';
			echo '<span>'._e('Hit ratio', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['all']['hits_percents'].'<br/>';
			echo '<span>'._e('Base capture points', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['all']['capture_points'].'<br/>';
			echo '<span>'._e('Base defense points', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['all']['dropped_capture_points'].'<br/>';
			echo '<span>'._e('Penetrations', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['all']['piercings'].'<br/>';
			echo '<span>'._e('Battles survived', $this->text_domain).':</span> '.$data['data'][$this->get_acc_id($nickname)]['statistics']['all']['survived_battles'].'<br/>';
			if($profile_link)	{
				echo '<br/><a title="'.$title.'" target="_blank" href="http://worldoftanks.ru/community/accounts/'.$this->get_acc_id($nickname).'-'.$nickname.'/"><img src="https://worldoftanks.ru/dcont/fb/signatures/wotsigna006.jpg" alt="'.$title.'"/></a>';
			}
			
			echo $args['after_widget'];
		}
	}

	public function update($new_instance, $old_instance)	{
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		$instance['nickname'] = (!empty($new_instance['nickname'])) ? strip_tags($new_instance['nickname']) : '';
		$instance['profile_link'] = (!empty($new_instance['profile_link'])) ? strip_tags($new_instance['profile_link']) : '';
		
		return $instance;
	}

	public function text_domain()	{
		load_plugin_textdomain($this->text_domain, false, dirname(plugin_basename( __FILE__ )).'/lang/');
	}
	
	private function get_stat($name, $mode)	{
		switch($mode)	{
			case 'basic':	
			$api_stat = 'https://api.worldoftanks.ru/wot/account/info/?application_id='.$this->defaults['app_id'].'&account_id='.$this->get_acc_id($name);
			break;
		}
		return $this->get_response($api_stat);
	}
	
	private function get_acc_id($name)	{
		$res = $this->get_response('http://api.worldoftanks.ru/wot/account/list/?application_id='.$this->defaults['app_id'].'&search='.$name.'&limit=1');
		return $res['data'][0]['account_id'];
	}
	
	private function get_response($url)	{
		$response = wp_remote_get($url);
		return json_decode($response['body'], true);
	}

}

?>
