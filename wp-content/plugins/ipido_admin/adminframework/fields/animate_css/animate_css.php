<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Animate.css
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_animate_css extends CSFramework_Options {

    /**
     * CSFramework_Option_animate_css constructor.
     * @param array  $field
     * @param string $value
     * @param string $unique
     */
    public function __construct($field = array(), $value = '', $unique = '') {
        parent::__construct($field, $value, $unique);
    }

    public function output() {
        $this->element_before();

        echo csf_add_element(array(
            'pseudo'    => true,
            'id'        => $this->field['id'],
            'type'      => 'select',
            'name'		=> $this->element_name(),
            'options'   => $this->animation_styles(),
            'value'     => $this->element_value(),
        ));

        echo '<div class="animation-preview">';
        echo '<h3 contentEditable="true">Animate.css</h3>';
        echo '</div>';

        $this->element_after();
    }

    protected function animation_styles() {
        return apply_filters('csf_animation_styles', array(
            'Attention Seekers'  => array(
                "bounce"     => 'bounce',
                "flash"      => 'flash',
                "pulse"      => 'pulse',
                "rubberBand" => 'rubberBand',
                "shake"      => 'shake',
                "swing"      => 'swing',
                "tada"       => 'tada',
                "wobble"     => 'wobble',
                "jello"      => 'jello',
            ),
            'Bouncing Entrances' => array(
                "bounceIn"      => 'bounceIn',
                "bounceInDown"  => 'bounceInDown',
                "bounceInLeft"  => 'bounceInLeft',
                "bounceInRight" => 'bounceInRight',
                "bounceInUp"    => 'bounceInUp',
            ),
            'Bouncing Exits'     => array(
                "bounceOut"      => 'bounceOut',
                "bounceOutDown"  => 'bounceOutDown',
                "bounceOutLeft"  => 'bounceOutLeft',
                "bounceOutRight" => 'bounceOutRight',
                "bounceOutUp"    => 'bounceOutUp',
            ),
            'Fading Entrances'   => array(
                "fadeIn"         => 'fadeIn',
                "fadeInDown"     => 'fadeInDown',
                "fadeInDownBig"  => 'fadeInDownBig',
                "fadeInLeft"     => 'fadeInLeft',
                "fadeInLeftBig"  => 'fadeInLeftBig',
                "fadeInRight"    => 'fadeInRight',
                "fadeInRightBig" => 'fadeInRightBig',
                "fadeInUp"       => 'fadeInUp',
                "fadeInUpBig"    => 'fadeInUpBig',
            ),
            'Fading Exits'       => array(
                "fadeOut"         => 'fadeOut',
                "fadeOutDown"     => 'fadeOutDown',
                "fadeOutDownBig"  => 'fadeOutDownBig',
                "fadeOutLeft"     => 'fadeOutLeft',
                "fadeOutLeftBig"  => 'fadeOutLeftBig',
                "fadeOutRight"    => 'fadeOutRight',
                "fadeOutRightBig" => 'fadeOutRightBig',
                "fadeOutUp"       => 'fadeOutUp',
                "fadeOutUpBig"    => 'fadeOutUpBig',
            ),
            "Flippers"           => array(
                "flip"     => 'flip',
                "flipInX"  => 'flipInX',
                "flipInY"  => 'flipInY',
                "flipOutX" => 'flipOutX',
                "flipOutY" => 'flipOutY',
            ),
            "Lightspeed"         => array(
                "lightSpeedIn"  => 'lightSpeedIn',
                "lightSpeedOut" => 'lightSpeedOut',
            ),
            "Rotating Entrances" => array(
                "rotateIn"          => 'rotateIn',
                "rotateInDownLeft"  => 'rotateInDownLeft',
                "rotateInDownRight" => 'rotateInDownRight',
                "rotateInUpLeft"    => 'rotateInUpLeft',
                "rotateInUpRight"   => 'rotateInUpRight',
            ),
            "Rotating Exits"     => array(
                "rotateOut"          => 'rotateOut',
                "rotateOutDownLeft"  => 'rotateOutDownLeft',
                "rotateOutDownRight" => 'rotateOutDownRight',
                "rotateOutUpLeft"    => 'rotateOutUpLeft',
                "rotateOutUpRight"   => 'rotateOutUpRight',
            ),
            "Sliding Entrances"  => array(
                "slideInUp"    => 'slideInUp',
                "slideInDown"  => 'slideInDown',
                "slideInLeft"  => 'slideInLeft',
                "slideInRight" => 'slideInRight',

            ),
            "Sliding Exits"      => array(
                "slideOutUp"    => 'slideOutUp',
                "slideOutDown"  => 'slideOutDown',
                "slideOutLeft"  => 'slideOutLeft',
                "slideOutRight" => 'slideOutRight',

            ),
            "Zoom Entrances"     => array(
                "zoomIn"      => 'zoomIn',
                "zoomInDown"  => 'zoomInDown',
                "zoomInLeft"  => 'zoomInLeft',
                "zoomInRight" => 'zoomInRight',
                "zoomInUp"    => 'zoomInUp',
            ),
            "Zoom Exits"         => array(
                "zoomOut"      => 'zoomOut',
                "zoomOutDown"  => 'zoomOutDown',
                "zoomOutLeft"  => 'zoomOutLeft',
                "zoomOutRight" => 'zoomOutRight',
                "zoomOutUp"    => 'zoomOutUp',
            ),
            "Specials"           => array(
                "hinge"        => 'hinge',
                "jackInTheBox" => 'jackInTheBox',
                "rollIn"       => 'rollIn',
                "rollOut"      => 'rollOut',
            ),
        ));
    }
}