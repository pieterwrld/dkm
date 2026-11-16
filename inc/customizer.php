<?php
/**
 * DKM Theme Customizer
 *
 * @package DKM
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'dkm_customize_register');

function dkm_customize_register(WP_Customize_Manager $wp_customize): void {

    $wp_customize->add_panel('dkm_panel', [
        'title'    => __('De Klokjesman', 'dkm'),
        'priority' => 30,
    ]);

    // --- Announcement Bar ---
    $wp_customize->add_section('dkm_announcement', [
        'title' => __('Aankondigingsbalk', 'dkm'),
        'panel' => 'dkm_panel',
    ]);

    $wp_customize->add_setting('dkm_announcement_enabled', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('dkm_announcement_enabled', [
        'label'   => __('Toon aankondigingsbalk', 'dkm'),
        'section' => 'dkm_announcement',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting('dkm_announcement_text', [
        'default'           => 'Gratis verzending boven €150 | Veilig betalen via iDEAL',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('dkm_announcement_text', [
        'label'   => __('Aankondigingstekst', 'dkm'),
        'section' => 'dkm_announcement',
        'type'    => 'text',
    ]);

    // --- Contact Info ---
    $wp_customize->add_section('dkm_contact', [
        'title' => __('Contactgegevens', 'dkm'),
        'panel' => 'dkm_panel',
    ]);

    $wp_customize->add_setting('dkm_whatsapp_number', [
        'default'           => '+31647974408',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('dkm_whatsapp_number', [
        'label'   => __('WhatsApp nummer', 'dkm'),
        'section' => 'dkm_contact',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('dkm_email', [
        'default'           => 'info@deklokjesman.com',
        'sanitize_callback' => 'sanitize_email',
    ]);
    $wp_customize->add_control('dkm_email', [
        'label'   => __('E-mailadres', 'dkm'),
        'section' => 'dkm_contact',
        'type'    => 'email',
    ]);

    $wp_customize->add_setting('dkm_whatsapp_message', [
        'default'           => 'Hallo, ik heb een vraag over',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('dkm_whatsapp_message', [
        'label'   => __('WhatsApp standaardbericht', 'dkm'),
        'section' => 'dkm_contact',
        'type'    => 'text',
    ]);

    // --- Social Media ---
    $wp_customize->add_section('dkm_social', [
        'title' => __('Social media', 'dkm'),
        'panel' => 'dkm_panel',
    ]);

    $wp_customize->add_setting('dkm_snapchat_url', [
        'default'           => 'https://snapchat.com/t/XvOpgrMG',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('dkm_snapchat_url', [
        'label'   => __('Snapchat URL', 'dkm'),
        'section' => 'dkm_social',
        'type'    => 'url',
    ]);

    $wp_customize->add_setting('dkm_whatsapp_link', [
        'default'           => 'https://wa.link/q23dxa',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('dkm_whatsapp_link', [
        'label'   => __('WhatsApp link (community)', 'dkm'),
        'section' => 'dkm_social',
        'type'    => 'url',
    ]);

    // --- Homepage ---
    $wp_customize->add_section('dkm_homepage', [
        'title' => __('Homepage', 'dkm'),
        'panel' => 'dkm_panel',
    ]);

    $wp_customize->add_setting('dkm_hero_title', [
        'default'           => 'Luxe Horloges',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('dkm_hero_title', [
        'label'   => __('Hero titel', 'dkm'),
        'section' => 'dkm_homepage',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('dkm_hero_subtitle', [
        'default'           => 'Zorgvuldig geselecteerde replica horloges van de hoogste kwaliteit voor de echte liefhebber.',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('dkm_hero_subtitle', [
        'label'   => __('Hero subtitel', 'dkm'),
        'section' => 'dkm_homepage',
        'type'    => 'textarea',
    ]);

    $wp_customize->add_setting('dkm_featured_count', [
        'default'           => 8,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('dkm_featured_count', [
        'label'   => __('Aantal uitgelichte producten', 'dkm'),
        'section' => 'dkm_homepage',
        'type'    => 'number',
        'input_attrs' => ['min' => 4, 'max' => 24, 'step' => 4],
    ]);
}
