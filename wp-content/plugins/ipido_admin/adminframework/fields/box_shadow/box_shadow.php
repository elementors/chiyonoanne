<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Box Shadow
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_box_shadow extends CSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		echo $this->element_before();
		// box-shadow: none|h-shadow v-shadow blur spread color |inset|initial|inherit;
		$settings = array(
			'hshadow'		=> ( isset($this->field['settings']['hshadow'])) ? false : true,
			'vshadow'		=> ( isset($this->field['settings']['vshadow'])) ? false : true,
			'blur'			=> ( isset($this->field['settings']['blur'])) ? false : true,
			'spread'		=> ( isset($this->field['settings']['spread'])) ? false : true,
			'color'			=> ( isset($this->field['settings']['color'])) ? false : true,
			'type'			=> ( isset($this->field['settings']['type'])) ? false : true,
			'unit'			=> ( empty($this->field['settings']['unit'])) ? true : '',
		);

		$defaults_value = array(
			'hshadow'		=> '',
			'vshadow'		=> '',
			'blur'			=> '',
			'spread'		=> '',
			'color'			=> '',
			'type'			=> '',
			'unit'			=> '',
		);

		$value				= wp_parse_args( $this->element_value(), $defaults_value );
		$value_hshadow		= $value['hshadow'];
		$value_vshadow		= $value['vshadow'];
		$value_blur			= $value['blur'];
		$value_spread		= $value['spread'];
		$value_color		= $value['color'];
		$value_type			= $value['type'];
		$value_unit			= $value['unit'];
		$is_chosen			= ( isset( $this->field['chosen'] ) && $this->field['chosen'] === false ) ? '' : 'chosen ';
		$chosen_rtl			= ( is_rtl() && ! empty( $is_chosen ) ) ? 'chosen-rtl ' : '';

		echo '<div class="csf-box_shadow csf-multifield">';

		if ($settings['hshadow'] === true) {
			echo csf_add_element( array(
				'pseudo'	=> true,
				'type'		=> 'text_addon',
				'name'		=> $this->element_name('[hshadow]'),
				'settings'	=> array(
					'type'			=> 'append',
					'addon_value'	=> $value_unit,
				),
				'value'		=> $value_hshadow,
				'attributes' => [
					'placeholder' => 'hor'
				],
				'before'	=> '<label>'.__('X Offset').'</label>',
			) );
		}
		if ($settings['vshadow'] === true) {
			echo csf_add_element( array(
				'pseudo'	=> true,
				'type'		=> 'text_addon',
				'name'		=> $this->element_name('[vshadow]'),
				'settings'	=> array(
					'type'			=> 'append',
					'addon_value'	=> $value_unit,
				),
				'value'		=> $value_vshadow,
				'attributes' => [
					'placeholder' => 'vert'
				],
				'before'	=> '<label>'.__('Y Offset').'</label>',
			) );
		}
		if ($settings['blur'] === true) {
			echo csf_add_element( array(
				'pseudo'	=> true,
				'type'		=> 'text_addon',
				'name'		=> $this->element_name('[blur]'),
				'settings'	=> array(
					'type'			=> 'append',
					'addon_value'	=> $value_unit,
				),
				'value'		=> $value_blur,
				'attributes' => [
					'placeholder' => 'blur'
				],
				'before'	=> '<label>'.__('Blur').'</label>',
			) );
		}
		if ($settings['spread'] === true) {
			echo csf_add_element( array(
				'pseudo'	=> true,
				'type'		=> 'text_addon',
				'name'		=> $this->element_name('[spread]'),
				'settings'	=> array(
					'type'			=> 'append',
					'addon_value'	=> $value_unit,
				),
				'value'		=> $value_spread,
				'attributes' => [
					'placeholder' => 'spread'
				],
				'before'	=> '<label>'.__('Spread').'</label>',
			) );
		}
		if ($settings['color'] === true) {
			echo csf_add_element( array(
				'pseudo'		=> true,
				'id'			=> $this->field['id'].'_color',
				'type'			=> 'color_picker',
				'name'			=> $this->element_name('[color]'),
				'attributes'	=> array(
					'data-atts'		=> 'boxshadowcolor',
				),
				'value'			=> $this->value['color'],
				'default'		=> ( isset( $this->field['default']['color'] ) ) ? $this->field['default']['color'] : '',
				'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
				'before'	=> '<label>'.__('Color').'</label>',
			) );
		}
		if ($settings['type'] === true) {
			echo csf_add_element( array(
				'pseudo'	=> true,
				'type'		=> 'select',
				'name'		=> $this->element_name('[type]'),
				'options'	=> array(
					'initial'	=> 'initial',
					'inherit'	=> 'inherit',
					'inset'		=> 'inset',
				),
				'value'		=> $value_type,
				'before'	=> '<label>'.__('Type').'</label>',
			) );
		}
		if ($settings['unit'] === true) {
			echo csf_add_element( array(
				'pseudo'	=> true,
				'type'		=> 'select',
				'name'		=> $this->element_name('[unit]'),
				'options'	=> array(
					'em'	=> 'em',
					'px'	=> 'px',
					'%'		=> '%',
				),
				'value'		=> $value_unit,
				'before'	=> '<label>'.__('Unit').'</label>',
			) );
		}

		echo '</div>';
		echo $this->element_after();

	}

}