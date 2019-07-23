<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'settings' => array(
        'type' => 'group',
        'options' => array(
            'icon' => array(
                'type' => 'icon-v2',
                'preview_size' => 'small',
                'modal_size' => 'medium',
                'label' => esc_html__('Speciality Icon', 'docdirect'),
                'desc' => esc_html__('Choose speciality icon. leave it empty to hide.', 'docdirect'),
            ),
        )
    ),
);