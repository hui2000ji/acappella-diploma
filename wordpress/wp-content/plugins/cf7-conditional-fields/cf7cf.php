<?php

class CF7CF {
    private $hidden_fields = array();
    private $visible_groups = array();
    private $hidden_groups = array();
    private $repeaters = array();

    function __construct() {

	    // can't use wpcf7_enqueue_scripts hook, because it's possible that people
	    // want to disable the CF7 scripts. but in this case Conditional fields should still work.
        // add_action('wpcf7_enqueue_scripts', array(__CLASS__, 'enqueue_js')); // <-- don't use this

	    // Enqueue_scripts moved to function outside class.

//	    add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_js'), 20);
//	    add_action('wpcf7_enqueue_styles', array(__CLASS__, 'enqueue_css'));

        // Register shortcodes
        add_action('wpcf7_init', array(__CLASS__, 'add_shortcodes'));

        // Tag generator
        add_action('admin_init', array(__CLASS__, 'tag_generator'), 590);

        // compatibility with CF7 multi-step forms by Webhead LLC.
        add_filter( 'wpcf7_posted_data', array($this,'cf7msm_merge_post_with_cookie'), 8, 1 );

        // compatibility with CF7 Multi Step by NinjaTeam https://wordpress.org/plugins/cf7-multi-step/
        add_action('wp_ajax_cf7mls_validation', array($this,'cf7mls_validation_callback'),9);
        add_action('wp_ajax_nopriv_cf7mls_validation', array($this,'cf7mls_validation_callback'),9);

        // check which fields are hidden during form submission and change some stuff accordingly
        add_filter( 'wpcf7_posted_data', array($this, 'remove_hidden_post_data') );

        add_filter( 'wpcf7_validate', array($this, 'skip_validation_for_hidden_fields'), 2, 2 );

	    // validation messages
	    add_action('wpcf7_config_validator_validate', array($this,'wpcf7cf_config_validator_validate'));


	    add_action("wpcf7_before_send_mail", [$this, 'hide_hidden_mail_fields'], 10, 3);

        register_activation_hook(__FILE__, array($this, 'activate'));

        if (is_admin()) {
            require_once dirname(__FILE__) . '/admin.php';
        }
    }



	/**
	 * Suppress invalid mailbox syntax errors on fields that contain existing conditional
	 */
    function wpcf7cf_config_validator_validate(WPCF7_ConfigValidator $wpcf7_config_validator) {

    	// TODO: For now we kill every syntax error once a [groupname] tag is detected.
	    //       Ideally, this function should check each string inside the group for invalid syntax.
	    // TODO 2: ajax validation not working yet, because $cf->scan_form_tags() does not seem to contain group tags if it's an ajax request. Need to investigate.

	    $cf = $wpcf7_config_validator->contact_form();
	    $all_group_tags = $cf->scan_form_tags();

    	foreach ($wpcf7_config_validator->collect_error_messages() as $err_type => $err) {

//    	    print_r($err_type);

		    $parts = explode('.',$err_type);

		    $property = $parts[0];

		    if ($property == 'form') continue; // the 'form' field can be safely validated by CF7. No need to suppress it.

		    $sub_prop = $parts[1];
		    $prop_val = $cf->prop($property)[$sub_prop];


		    // TODO 2: Dirty hack. Because of TODO 2 we are just going to kill the error message if we detect the string '[/'
		    //         Start removing here.
		    if (strpos($prop_val, '[/') !== false) {
			    $wpcf7_config_validator->remove_error($err_type, WPCF7_ConfigValidator::error_invalid_mailbox_syntax);
				continue;
		    }
		    // TODO 2: Stop removing here. and uncomment code below.

//		    foreach ($all_group_tags as $form_tag) {
//				if (strpos($prop_val, '['.$form_tag->name.']') !== false) {
//					$wpcf7_config_validator->remove_error($err_type, WPCF7_ConfigValidator::error_invalid_mailbox_syntax);
//				}
//		    }

	    }

    	return new WPCF7_ConfigValidator($wpcf7_config_validator->contact_form());
    }

    function activate() {
        //add options with add_option and stuff
    }

    public static function add_shortcodes() {
        if (function_exists('wpcf7_add_form_tag'))
            wpcf7_add_form_tag('group', array(__CLASS__, 'shortcode_handler'), true);
        else if (function_exists('wpcf7_add_shortcode')) {
            wpcf7_add_shortcode('group', array(__CLASS__, 'shortcode_handler'), true);
        } else {
            throw new Exception('functions wpcf7_add_form_tag and wpcf7_add_shortcode not found.');
        }
    }

    function group_shortcode_handler( $atts, $content = "" ) {
        return $content;
    }

    public static function shortcode_handler($tag) {
        //$tag = new WPCF7_Shortcode($tag);
        $tag = new WPCF7_FormTag($tag);
        //ob_start();
        //print_r($tag);
        //return print_r($tag, true);
        return $tag->content;
    }


    public static function tag_generator() {
        if (! function_exists( 'wpcf7_add_tag_generator'))
            return;

        wpcf7_add_tag_generator('group',
            __('Conditional Fields Group', 'wpcf7cf'),
            'wpcf7-tg-pane-group',
            array(__CLASS__, 'tg_pane')
        );

        do_action('wpcf7cf_tag_generator');
    }

    static function tg_pane( $contact_form, $args = '' ) {
        $args = wp_parse_args( $args, array() );
        $type = 'group';

        $description = __( "Generate a group tag to group form elements that can be shown conditionally.", 'cf7cf' );

        include 'tg_pane_group.php';
    }

    /**
     * Remove validation requirements for fields that are hidden at the time of form submission.
     * Called using add_filter( 'wpcf7_validate_[tag_type]', array($this, 'skip_validation_for_hidden_fields'), 2, 2 );
     * where the priority of 2 causes this to kill any validations with a priority higher than 2
     *
     * @param $result
     * @param $tag
     *
     * @return mixed
     */
    function skip_validation_for_hidden_fields($result, $tags) {

        if(isset($_POST)) {
            $this->set_hidden_fields_arrays($_POST);
        }

        $invalid_fields = $result->get_invalid_fields();
        $return_result = new WPCF7_Validation();

        if (count($this->hidden_fields) == 0 || !is_array($invalid_fields) || count($invalid_fields) == 0) {
            $return_result = $result;
        } else {
            foreach ($invalid_fields as $invalid_field_key => $invalid_field_data) {
                if (!in_array($invalid_field_key, $this->hidden_fields)) {
                    // the invalid field is not a hidden field, so we'll add it to the final validation result
                    $return_result->invalidate($invalid_field_key, $invalid_field_data['reason']);
                }
            }
        }

        return apply_filters('wpcf7cf_validate', $return_result, $tags);

    }


    /**
     * When a CF7 form is posted, check the form for hidden fields, then remove those fields from the post data
     *
     * @param $posted_data
     *
     * @return mixed
     */
    function remove_hidden_post_data($posted_data) {
        $this->set_hidden_fields_arrays($posted_data);

// TODO: activating the code below will change the behaviour of the plugin, as all hidden fields will not be POSTed.
//       We might consider activating this based on a setting in the future. But for now, we're not gonna use it.

//        foreach( $this->hidden_fields as $name => $value ) {
//            unset( $posted_data[$value] ); // Yes, it should be $value, not $name. https://github.com/pwkip/contact-form-7-conditional-fields/pull/17
//        }

        return $posted_data;
    }

    function cf7msm_merge_post_with_cookie($posted_data) {

        if (!function_exists('cf7msm_get') || !key_exists('cf7msm_posted_data',$_COOKIE)) return $posted_data;

        if (!$posted_data) {
            $posted_data = WPCF7_Submission::get_instance()->get_posted_data();
        }

        // this will temporarily set the hidden fields data to the posted_data.
        // later this function will be called again with the updated posted_data
        $this->set_hidden_fields_arrays($posted_data);

        // get cookie data
        $cookie_data = cf7msm_get('cf7msm_posted_data');
        $cookie_data_hidden_group_fields = json_decode(stripslashes($cookie_data['_wpcf7cf_hidden_group_fields']));
        $cookie_data_hidden_groups = json_decode(stripslashes($cookie_data['_wpcf7cf_hidden_groups']));
        $cookie_data_visible_groups = json_decode(stripslashes($cookie_data['_wpcf7cf_visible_groups']));

        // remove all the currently posted data from the cookie data (we don't wanna add it twice)
        $cookie_data_hidden_group_fields = array_diff($cookie_data_hidden_group_fields, array_keys($posted_data));
        $cookie_data_hidden_groups = array_diff((array) $cookie_data_hidden_groups, $this->hidden_groups, $this->visible_groups);
        $cookie_data_visible_groups = array_diff((array) $cookie_data_visible_groups, $this->hidden_groups, $this->visible_groups);

        // update current post data with cookie data
        $posted_data['_wpcf7cf_hidden_group_fields'] = addslashes(json_encode(array_merge((array) $cookie_data_hidden_group_fields, $this->hidden_fields)));
        $posted_data['_wpcf7cf_hidden_groups'] = addslashes(json_encode(array_merge((array) $cookie_data_hidden_groups, $this->hidden_groups)));
        $posted_data['_wpcf7cf_visible_groups'] = addslashes(json_encode(array_merge((array) $cookie_data_visible_groups, $this->visible_groups)));

        return $posted_data;
    }

    // compatibility with CF7 Multi Step by NinjaTeam https://wordpress.org/plugins/cf7-multi-step/
    function cf7mls_validation_callback() {
        $this->set_hidden_fields_arrays($_POST);
    }

    /**
     * Finds the currently submitted form and set the hidden_fields variables accoringly
     *
     * @param bool|array $posted_data
     */
    function set_hidden_fields_arrays($posted_data = false) {

        if (!$posted_data) {
            $posted_data = WPCF7_Submission::get_instance()->get_posted_data();
        }

        $hidden_fields = json_decode(stripslashes($posted_data['_wpcf7cf_hidden_group_fields']));
        if (is_array($hidden_fields) && count($hidden_fields) > 0) {
            foreach ($hidden_fields as $field) {
                $this->hidden_fields[] = $field;
                if (wpcf7cf_endswith($field, '[]')) {
                    $this->hidden_fields[] = substr($field,0,strlen($field)-2);
                }
            }
        }
        $this->hidden_groups = json_decode(stripslashes($posted_data['_wpcf7cf_hidden_groups']));
        $this->visible_groups = json_decode(stripslashes($posted_data['_wpcf7cf_visible_groups']));
        $this->repeaters = json_decode(stripslashes($posted_data['_wpcf7cf_repeaters']));
        $this->steps = json_decode(stripslashes($posted_data['_wpcf7cf_steps']));
    }

	function hide_hidden_mail_fields($form,$abort,$submission) {
		$props = $form->get_properties();
		$mails = ['mail','mail_2','messages'];
		foreach ($mails as $mail) {
			foreach ($props[$mail] as $key=>$val) {

                $parser = new Wpcf7cfMailParser($val, $this->visible_groups, $this->hidden_groups, $this->repeaters, $_POST);

				// $props[$mail][$key] = preg_replace_callback(WPCF7CF_REGEX_MAIL_GROUP, array($this, 'hide_hidden_mail_fields_regex_callback'), $val );
				$props[$mail][$key] = $parser->getParsedMail();
			}
        }
        //$props['mail']['body'] = 'xxx';
		$form->set_properties($props);
	}

    function hide_hidden_mail_fields_regex_callback ( $matches ) {
        $name = $matches[1];
        $content = $matches[2];
        if ( in_array( $name, $this->hidden_groups ) ) {
            // The tag name represents a hidden group, so replace everything from [tagname] to [/tagname] with nothing
            return '';
        } elseif ( in_array( $name, $this->visible_groups ) ) {
            // The tag name represents a visible group, so remove the tags themselves, but return everything else
            // instead of just returning the $content, return the preg_replaced content :)
            return preg_replace_callback(WPCF7CF_REGEX_MAIL_GROUP, array($this, 'hide_hidden_mail_fields_regex_callback'), $content );
        } else {
            // The tag name doesn't represent a group that was used in the form. Leave it alone (return the entire match).
            return $matches[0];
        }
    }

    public static function parse_conditions($string, $format='array') {
        // Parse stuff like "show [g1] if [field] equals 2" to Array

        preg_match_all(WPCF7CF_REGEX_CONDITIONS, $string, $matches);

        $conditions = [];

        $prev_then_field = '';
        foreach ($matches[0] as $i=>$line) {
            $then_field = $matches[1][$i];
            $if_field   = $matches[2][$i];
            $operator   = $matches[3][$i];
            $if_value   = $matches[4][$i];

            $index = count($conditions);

            if ($then_field == '') {
                $index = $index -1;
                $then_field = $prev_then_field;
            } else {
                $conditions[$index]['then_field'] = $then_field;
            }

            $conditions[$index]['and_rules'][] = [
                'if_field' => $if_field,
                'operator' => $operator,
                'if_value' => $if_value,
            ];

            $prev_then_field = $then_field;

        }

        $conditions = array_values($conditions);

        if ($format == 'array') {
            return $conditions;
        } else if ($format == 'json') {
            return json_encode($conditions);
        }
    }

    /**
     * load the conditions from the form's post_meta
     *
     * @param string $form_id
     * @return void
     */
    public static function getConditions($form_id) {
        // make sure conditions are an array.
        $options = get_post_meta($form_id,'wpcf7cf_options',true);
        return is_array($options) ? $options : array(); // the meta key 'wpcf7cf_options' is a bit misleading at this point, because it only holds the form's conditions, no other options/settings
    }

    
    /**
     * save the conditions to the form's post_meta
     *
     * @param string $form_id
     * @return void
     */
    public static function setConditions($form_id, $conditions) {
        return update_post_meta($form_id,'wpcf7cf_options',$conditions); // the meta key 'wpcf7cf_options' is a bit misleading at this point, because it only holds the form's conditions, no other options/settings
    }
}

new CF7CF;

add_filter( 'wpcf7_contact_form_properties', 'wpcf7cf_properties', 10, 2 );

function wpcf7cf_properties($properties, $wpcf7form) {
	// TODO: This function is called serveral times. The problem is that the filter is called each time we call get_properties() on a contact form.
	// TODO: I haven't found a better way to solve this problem yet, any suggestions or push requests are welcome. (same problem in PRO/repeater.php)
	if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) { // TODO: kind of hacky. maybe find a better solution. Needed because otherwise the group tags will be replaced in the editor as well.
        $form = $properties['form'];

	    $form_parts = preg_split('/(\[\/?group(?:\]|\s.*?\]))/',$form, -1,PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

	    ob_start();

	    $stack = array();

	    foreach ($form_parts as $form_part) {
	    	if (substr($form_part,0,7) == '[group ') {
	    		$tag_parts = explode(' ',rtrim($form_part,']'));

	    		array_shift($tag_parts);

	    		$tag_id = $tag_parts[0];
	    		$tag_html_type = 'div';
	    		$tag_html_data = array();

	    		foreach ($tag_parts as $i => $tag_part) {
	    			if ($i==0) continue;
					else if ($tag_part == 'inline') $tag_html_type = 'span';
					else if ($tag_part == 'clear_on_hide') $tag_html_data[] = 'data-clear_on_hide';
			    }

			    array_push($stack,$tag_html_type);

			    echo '<'.$tag_html_type.' data-id="'.$tag_id.'" data-orig_data_id="'.$tag_id.'" '.implode(' ',$tag_html_data).' data-class="wpcf7cf_group">';
		    } else if ($form_part == '[/group]') {
	    		echo '</'.array_pop($stack).'>';
		    } else {
	    		echo $form_part;
		    }
	    }

        $properties['form'] = ob_get_clean();
    }
    return $properties;
}

add_action('wpcf7_form_hidden_fields', 'wpcf7cf_form_hidden_fields',10,1);

function wpcf7cf_form_hidden_fields($hidden_fields) {

    $current_form = wpcf7_get_current_contact_form();
    $current_form_id = $current_form->id();

    $options = array(
        'form_id' => $current_form_id,
        'conditions' => CF7CF::getConditions($current_form_id),
        'settings' => get_option(WPCF7CF_OPTIONS)
    );

    unset($options['settings']['license_key']); // don't show license key in the source code duh.

	return array_merge($hidden_fields, array(
        '_wpcf7cf_hidden_group_fields' => '',
        '_wpcf7cf_hidden_groups' => '',
        '_wpcf7cf_visible_groups' => '',
        '_wpcf7cf_repeaters' => '[]',
        '_wpcf7cf_steps' => '{}',
        '_wpcf7cf_options' => ''.json_encode($options),
    ));
}

function wpcf7cf_endswith($string, $test) {
    $strlen = strlen($string);
    $testlen = strlen($test);
    if ($testlen > $strlen) return false;
    return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
}

add_filter( 'wpcf7_form_tag_data_option', 'wpcf7cf_form_tag_data_option', 10, 3 );

function wpcf7cf_form_tag_data_option($output, $args, $nog) {
	$data = array();
	return $data;
}

/* Scripts & Styles */

function wpcf7cf_load_js() {
	return apply_filters( 'wpcf7cf_load_js', WPCF7CF_LOAD_JS );
}

function wpcf7cf_load_css() {
	return apply_filters( 'wpcf7cf_load_css', WPCF7CF_LOAD_CSS );
}

add_action( 'wp_enqueue_scripts', 'wpcf7cf_do_enqueue_scripts', 20, 0 );

function wpcf7cf_do_enqueue_scripts() {
	if ( wpcf7cf_load_js() ) {
		wpcf7cf_enqueue_scripts();
	}

	if ( wpcf7cf_load_css() ) {
		wpcf7cf_enqueue_styles();
	}
}

function wpcf7cf_enqueue_scripts() {
	if (is_admin()) return;
	wp_enqueue_script('wpcf7cf-scripts', plugins_url('js/scripts.js', __FILE__), array('jquery'), WPCF7CF_VERSION, true);
	wp_localize_script('wpcf7cf-scripts', 'wpcf7cf_global_settings',
		array(
			'ajaxurl' => admin_url('admin-ajax.php'),
		)
	);

}

function wpcf7cf_enqueue_styles() {
	if (is_admin()) return;
	wp_enqueue_style('cf7cf-style', plugins_url('style.css', __FILE__), array(), WPCF7CF_VERSION);
}