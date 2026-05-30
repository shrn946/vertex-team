<?php
/**
 * Plugin Name: Vertex Team Carousel for Elementor
 * Description: Elementor team carousel widget using the original Swiper HTML, CSS, and JavaScript structure.
 * Version: 2.1.0
 * Author: Apex Themes Studio
 * Text Domain: vertex-team-carousel-elementor
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Team_Carousel_Elementor_Plugin {

	const VERSION = '2.1.0';
	const TEXT_DOMAIN = 'vertex-team-carousel-elementor';

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function init() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'missing_elementor_notice' ) );
			return;
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_assets' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_assets' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widget' ) );
	}

	public function missing_elementor_notice() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		echo '<div class="notice notice-warning is-dismissible"><p>' . esc_html__( 'Vertex Team Carousel for Elementor requires Elementor to be installed and activated.', self::TEXT_DOMAIN ) . '</p></div>';
	}

	public function register_assets() {
		wp_register_style(
			'tces-google-fonts',
			'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap',
			array(),
			null
		);

		wp_register_style(
			'tces-normalize',
			'https://public.codepenassets.com/css/normalize-5.0.0.min.css',
			array(),
			'5.0.0'
		);

		wp_register_style(
			'tces-swiper',
			'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.4/swiper-bundle.css',
			array(),
			'11.0.4'
		);

		wp_register_style(
			'tces-font-awesome',
			'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css',
			array(),
			'6.4.2'
		);

		wp_register_style(
			'tces-style',
			plugins_url( 'style.css', __FILE__ ),
			array( 'tces-google-fonts', 'tces-normalize', 'tces-swiper', 'tces-font-awesome' ),
			self::VERSION
		);

		wp_register_script(
			'tces-swiper',
			'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.4/swiper-bundle.min.js',
			array(),
			'11.0.4',
			true
		);

		wp_register_script(
			'tces-script',
			plugins_url( 'script.js', __FILE__ ),
			array( 'tces-swiper' ),
			self::VERSION,
			true
		);
	}

	public function register_widget( $widgets_manager ) {
		tces_load_team_carousel_widget_class();

		if ( class_exists( 'TCES_Team_Carousel_Widget' ) ) {
			$widgets_manager->register( new \TCES_Team_Carousel_Widget() );
		}
	}
}

function tces_load_team_carousel_widget_class() {
	if ( ! class_exists( '\Elementor\Widget_Base' ) || class_exists( 'TCES_Team_Carousel_Widget' ) ) {
		return;
	}

	class TCES_Team_Carousel_Widget extends \Elementor\Widget_Base {

		public function get_name() {
			return 'tces_team_carousel';
		}

		public function get_title() {
			return esc_html__( 'Team Carousel Slider', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN );
		}

		public function get_icon() {
			return 'eicon-slider-push';
		}

		public function get_categories() {
			return array( 'general' );
		}

		public function get_keywords() {
			return array( 'team', 'carousel', 'slider', 'swiper', 'people' );
		}

		public function get_style_depends() {
			return array( 'tces-style' );
		}

		public function get_script_depends() {
			return array( 'tces-script' );
		}

		protected function register_controls() {
			$this->start_controls_section(
				'section_people',
				array(
					'label' => esc_html__( 'Team Members', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
				)
			);

			$social_repeater = new \Elementor\Repeater();

			$social_repeater->add_control(
				'social_icon',
				array(
					'label'   => esc_html__( 'Icon', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'    => \Elementor\Controls_Manager::ICONS,
					'default' => array(
						'value'   => 'fab fa-facebook-f',
						'library' => 'fa-brands',
					),
				)
			);

			$social_repeater->add_control(
				'social_url',
				array(
					'label'         => esc_html__( 'URL', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'          => \Elementor\Controls_Manager::URL,
					'placeholder'   => 'https://example.com',
					'default'       => array(
						'url'         => '',
						'is_external' => true,
						'nofollow'    => true,
					),
					'show_external' => true,
				)
			);

			$repeater = new \Elementor\Repeater();

			$repeater->add_control(
				'image',
				array(
					'label'   => esc_html__( 'Image', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'    => \Elementor\Controls_Manager::MEDIA,
					'default' => array(
						'url' => \Elementor\Utils::get_placeholder_image_src(),
					),
				)
			);

			$repeater->add_control(
				'image_alt',
				array(
					'label'       => esc_html__( 'Image Alt Text', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
				)
			);

			$repeater->add_control(
				'name',
				array(
					'label'       => esc_html__( 'Name', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'default'     => esc_html__( 'Drew Houston', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'label_block' => true,
				)
			);

			$repeater->add_control(
				'position',
				array(
					'label'       => esc_html__( 'Position', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'default'     => esc_html__( 'Co-founder and CEO', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'label_block' => true,
				)
			);

			$repeater->add_control(
				'description',
				array(
					'label'   => esc_html__( 'Description', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'    => \Elementor\Controls_Manager::TEXTAREA,
					'default' => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
				)
			);

			$repeater->add_control(
				'social_icons',
				array(
					'label'       => esc_html__( 'Social Icons', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'        => \Elementor\Controls_Manager::REPEATER,
					'fields'      => $social_repeater->get_controls(),
					'title_field' => '{{{ social_icon.value }}}',
				)
			);

			$repeater->add_control(
				'enable_view_info_button',
				array(
					'label'        => esc_html__( 'Enable View Info Button', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'label_off'    => esc_html__( 'No', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$repeater->add_control(
				'button_text',
				array(
					'label'     => esc_html__( 'Button Text', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'      => \Elementor\Controls_Manager::TEXT,
					'default'   => esc_html__( 'View Info', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'condition' => array(
						'enable_view_info_button' => 'yes',
					),
				)
			);

			$repeater->add_control(
				'button_url',
				array(
					'label'         => esc_html__( 'Button Link', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'          => \Elementor\Controls_Manager::URL,
					'placeholder'   => 'https://example.com',
					'default'       => array(
						'url'         => '',
						'is_external' => false,
						'nofollow'    => false,
					),
					'show_external' => true,
					'condition'     => array(
						'enable_view_info_button' => 'yes',
					),
				)
			);

			$this->add_control(
				'people',
				array(
					'label'       => esc_html__( 'Slides', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'        => \Elementor\Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'default'     => $this->get_default_people(),
					'title_field' => '{{{ name }}}',
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_text',
				array(
					'label' => esc_html__( 'Text Style', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'text_align',
				array(
					'label'     => esc_html__( 'Text Alignment', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'      => \Elementor\Controls_Manager::CHOOSE,
					'options'   => array(
						'left'   => array( 'title' => esc_html__( 'Left', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ), 'icon' => 'eicon-text-align-left' ),
						'center' => array( 'title' => esc_html__( 'Center', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ), 'icon' => 'eicon-text-align-center' ),
						'right'  => array( 'title' => esc_html__( 'Right', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ), 'icon' => 'eicon-text-align-right' ),
					),
					'default'   => 'center',
					'selectors' => array(
						'{{WRAPPER}} .people__slide .swiper-slide .people__card .people__info' => 'text-align: {{VALUE}};',
						'{{WRAPPER}} .people__slide .swiper-slide .people__card .people__desc' => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'name_color',
				array(
					'label'     => esc_html__( 'Name Color', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .people__name' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				array(
					'name'           => 'name_typography',
					'label'          => esc_html__( 'Name Typography', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'selector'       => '{{WRAPPER}} .people__name',
					'fields_options' => array(
						'font_size' => array(
							'default' => array(
								'unit' => 'px',
								'size' => 26,
							),
						),
					),
				)
			);

			$this->add_control(
				'position_color',
				array(
					'label'     => esc_html__( 'Position Color', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .people__position' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				array(
					'name'     => 'position_typography',
					'label'    => esc_html__( 'Position Typography', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'selector' => '{{WRAPPER}} .people__position',
				)
			);

			$this->add_control(
				'description_color',
				array(
					'label'     => esc_html__( 'Description Color', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .people__desc' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				array(
					'name'     => 'description_typography',
					'label'    => esc_html__( 'Description Typography', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'selector' => '{{WRAPPER}} .people__desc',
				)
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				array(
					'name'           => 'item_background',
					'label'          => esc_html__( 'Item Background', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'types'          => array( 'classic', 'gradient' ),
					'selector'       => '{{WRAPPER}} .people__card',
					'fields_options' => array(
						'background' => array(
							'default' => 'gradient',
						),
						'color'      => array(
							'default' => '#752E2E',
						),
						'color_b'    => array(
							'default' => '#F2295B',
						),
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_social_icons',
				array(
					'label' => esc_html__( 'Social Icons Style', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'social_icon_size',
				array(
					'label'      => esc_html__( 'Icon Size', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'       => \Elementor\Controls_Manager::SLIDER,
					'range'      => array(
						'px' => array(
							'min' => 8,
							'max' => 80,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .people__social a i'   => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .people__social a svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'social_icon_color',
				array(
					'label'     => esc_html__( 'Icon Color', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'default'   => '#FFFFFF',
					'selectors' => array(
						'{{WRAPPER}} .people__social a'     => 'color: {{VALUE}};',
						'{{WRAPPER}} .people__social a svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'social_icon_hover_color',
				array(
					'label'     => esc_html__( 'Icon Hover Color', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .people__social a:hover'     => 'color: {{VALUE}};',
						'{{WRAPPER}} .people__social a:hover svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'social_icon_spacing',
				array(
					'label'      => esc_html__( 'Icon Spacing', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'       => \Elementor\Controls_Manager::SLIDER,
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 60,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .people__social li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_button',
				array(
					'label' => esc_html__( 'View Info Button Style', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				array(
					'name'     => 'button_typography',
					'label'    => esc_html__( 'Typography', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'selector' => '{{WRAPPER}} .people__btn a',
				)
			);

			$this->add_responsive_control(
				'button_align',
				array(
					'label'     => esc_html__( 'Alignment', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'      => \Elementor\Controls_Manager::CHOOSE,
					'options'   => array(
						'flex-start' => array(
							'title' => esc_html__( 'Left', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
							'icon'  => 'eicon-text-align-left',
						),
						'center'     => array(
							'title' => esc_html__( 'Center', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
							'icon'  => 'eicon-text-align-center',
						),
						'flex-end'   => array(
							'title' => esc_html__( 'Right', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'default'   => 'center',
					'selectors' => array(
						'{{WRAPPER}} .people__btn' => 'display: flex; justify-content: {{VALUE}};',
					),
				)
			);

			$this->start_controls_tabs( 'button_style_tabs' );

			$this->start_controls_tab(
				'button_style_tab_normal',
				array(
					'label' => esc_html__( 'Normal', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
				)
			);

			$this->add_control(
				'button_text_color',
				array(
					'label'     => esc_html__( 'Text Color', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .people__btn a' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'button_background_color',
				array(
					'label'     => esc_html__( 'Background Color', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .people__btn a' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'button_style_tab_hover',
				array(
					'label' => esc_html__( 'Hover', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
				)
			);

			$this->add_control(
				'button_hover_text_color',
				array(
					'label'     => esc_html__( 'Hover Text Color', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .people__btn a:hover' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'button_hover_background_color',
				array(
					'label'     => esc_html__( 'Hover Background Color', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .people__btn a:hover' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_responsive_control(
				'button_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .people__btn a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'button_padding',
				array(
					'label'      => esc_html__( 'Padding', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', 'rem', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .people__btn a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();
		}

		private function get_default_people() {
			return array(
				$this->default_person( 'https://images.pexels.com/photos/2182970/pexels-photo-2182970.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', '', 'Ariana Blake', 'Creative Director' ),
				$this->default_person( 'https://images.pexels.com/photos/1181424/pexels-photo-1181424.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', '', 'Noah Bennett', 'Head of Operations' ),
				$this->default_person( 'https://images.pexels.com/photos/3778876/pexels-photo-3778876.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', '', 'Sophia Carter', 'Lead Product Strategist' ),
				$this->default_person( 'https://images.pexels.com/photos/1587009/pexels-photo-1587009.jpeg', '', 'Ethan Walker', 'Chief Technology Officer' ),
				$this->default_person( 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', '', 'Maya Reynolds', 'Marketing Manager' ),
				$this->default_person( 'https://images.pexels.com/photos/3646160/pexels-photo-3646160.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', '', 'Liam Foster', 'Senior UI/UX Designer' ),
				$this->default_person( 'https://images.pexels.com/photos/1516680/pexels-photo-1516680.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', '', 'Zara Mitchell', 'Frontend Developer' ),
				$this->default_person( 'https://images.pexels.com/photos/10669639/pexels-photo-10669639.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', '', 'Daniel Hughes', 'Business Development Lead' ),
				$this->default_person( 'https://images.pexels.com/photos/1486064/pexels-photo-1486064.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', '', 'Chloe Morgan', 'Customer Success Manager' ),
			);
		}

		private function default_social_icons() {
			return array(
				array(
					'social_icon' => array(
						'value'   => 'fab fa-facebook-f',
						'library' => 'fa-brands',
					),
					'social_url'  => array(
						'url'         => '',
						'is_external' => true,
						'nofollow'    => true,
					),
				),
				array(
					'social_icon' => array(
						'value'   => 'fab fa-x-twitter',
						'library' => 'fa-brands',
					),
					'social_url'  => array(
						'url'         => '',
						'is_external' => true,
						'nofollow'    => true,
					),
				),
				array(
					'social_icon' => array(
						'value'   => 'fab fa-linkedin-in',
						'library' => 'fa-brands',
					),
					'social_url'  => array(
						'url'         => '',
						'is_external' => true,
						'nofollow'    => true,
					),
				),
			);
		}

		private function default_person( $image, $alt, $name, $position ) {
			return array(
				'image'                   => array( 'url' => $image ),
				'image_alt'               => $alt,
				'name'                    => $name,
				'position'                => $position,
				'description'             => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
				'social_icons'            => $this->default_social_icons(),
				'enable_view_info_button' => '',
				'button_text'             => esc_html__( 'View Info', Team_Carousel_Elementor_Plugin::TEXT_DOMAIN ),
				'button_url'              => array(
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				),
			);
		}

		private function get_link_attributes( $link ) {
			$attributes = array();

			if ( empty( $link['url'] ) ) {
				$attributes[] = 'href="#"';
				return implode( ' ', $attributes );
			}

			$attributes[] = 'href="' . esc_url( $link['url'] ) . '"';

			$rel = array();

			if ( ! empty( $link['is_external'] ) ) {
				$attributes[] = 'target="_blank"';
				$rel[]        = 'noopener';
			}

			if ( ! empty( $link['nofollow'] ) ) {
				$rel[] = 'nofollow';
			}

			if ( ! empty( $rel ) ) {
				$attributes[] = 'rel="' . esc_attr( implode( ' ', array_unique( $rel ) ) ) . '"';
			}

			return implode( ' ', $attributes );
		}

		protected function render() {
			$settings = $this->get_settings_for_display();
			$people   = ! empty( $settings['people'] ) && is_array( $settings['people'] ) ? $settings['people'] : $this->get_default_people();
			?>
	<div class="slider">
	  <div class="swiper people__slide">
	  <div class="swiper-wrapper">
			<?php foreach ( $people as $person ) : ?>
				<?php
				$image_url       = ! empty( $person['image']['url'] ) ? $person['image']['url'] : \Elementor\Utils::get_placeholder_image_src();
				$image_alt       = isset( $person['image_alt'] ) ? $person['image_alt'] : '';
				$name            = isset( $person['name'] ) ? $person['name'] : '';
				$position        = isset( $person['position'] ) ? $person['position'] : '';
				$description     = isset( $person['description'] ) ? $person['description'] : '';
				$social_icons    = ! empty( $person['social_icons'] ) && is_array( $person['social_icons'] ) ? $person['social_icons'] : array();
				$enable_button   = ! empty( $person['enable_view_info_button'] ) && 'yes' === $person['enable_view_info_button'];
				$button_text     = isset( $person['button_text'] ) ? $person['button_text'] : '';
				$button_url      = ! empty( $person['button_url'] ) && is_array( $person['button_url'] ) ? $person['button_url'] : array();
				?>
	    <div class="swiper-slide">
	      <div class="people__card">
	        <div class="people__image">
	          <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
	        </div>
	        <div class="people__info">
				<?php if ( ! empty( $social_icons ) ) : ?>
	          <ul class="people__social">
					<?php foreach ( $social_icons as $social_item ) : ?>
						<?php
						$social_icon = ! empty( $social_item['social_icon'] ) && is_array( $social_item['social_icon'] ) ? $social_item['social_icon'] : array();
						$social_url  = ! empty( $social_item['social_url'] ) && is_array( $social_item['social_url'] ) ? $social_item['social_url'] : array();

						if ( empty( $social_icon['value'] ) ) {
							continue;
						}
						?>
	            <li><a <?php echo wp_kses_post( $this->get_link_attributes( $social_url ) ); ?>><?php \Elementor\Icons_Manager::render_icon( $social_icon, array( 'aria-hidden' => 'true' ) ); ?></a></li>
					<?php endforeach; ?>
	          </ul>
				<?php endif; ?>
	          <h3 class="people__name"><?php echo esc_html( $name ); ?></h3>
	          <p class="people__position"><?php echo esc_html( $position ); ?></p>
	          <p class="people__desc"><?php echo esc_html( $description ); ?></p>
	        </div>
				<?php if ( $enable_button && ! empty( $button_text ) ) : ?>
	        <div class="people__btn">
	          <a <?php echo wp_kses_post( $this->get_link_attributes( $button_url ) ); ?>>
					<span class="people__btn-text"><?php echo esc_html( $button_text ); ?></span>
				</a>
	        </div>
				<?php endif; ?>
	      </div>
	    </div>
			<?php endforeach; ?>
	  </div>
	</div>
	</div>
			<?php
		}
	}
}

Team_Carousel_Elementor_Plugin::instance();
