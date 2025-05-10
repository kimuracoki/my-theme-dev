<?php

// Vite でビルドした React のアセットを読み込む
function mytheme_enqueue_assets() {
  wp_enqueue_style('mytheme-style', get_stylesheet_uri());

  $manifest_path = get_template_directory() . '/dist/.vite/manifest.json';
  if (!file_exists($manifest_path)) {
    return;
  }

  $manifest = json_decode(file_get_contents($manifest_path), true);
  $entry = $manifest['index.html'];

  // CSS の読み込み
  if (!empty($entry['css'])) {
    foreach ($entry['css'] as $i => $css_file) {
      wp_enqueue_style(
        'mytheme-style-' . $i,
        get_template_directory_uri() . '/dist/' . $css_file,
        [],
        null
      );
    }
  }

  // JS の読み込み
  if (!empty($entry['file'])) {
    wp_enqueue_script(
      'mytheme-react-app',
      get_template_directory_uri() . '/dist/' . $entry['file'],
      [],
      null,
      true
    );

    // React 側にカスタマイズデータを渡す
    wp_localize_script('mytheme-react-app', 'mythemeData', [
      'heroTitle' => get_theme_mod('hero_title', 'ようこそ！'),
      'heroImage' => get_theme_mod('hero_image', ''),
    ]);
  }
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_assets');


// カスタマイザーに設定項目を追加
function mytheme_customize_register($wp_customize) {
  // ヒーロータイトル
  $wp_customize->add_setting('hero_title', [
    'default' => 'ようこそ！',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('hero_title', [
    'label' => 'ヒーローセクションのタイトル',
    'section' => 'title_tagline',
    'type' => 'text',
  ]);

  // ヒーロー画像
  $wp_customize->add_setting('hero_image', [
    'default' => '',
    'sanitize_callback' => 'esc_url_raw',
  ]);
  $wp_customize->add_control(new WP_Customize_Image_Control(
    $wp_customize,
    'hero_image',
    [
      'label' => 'ヒーロー画像',
      'section' => 'title_tagline',
      'settings' => 'hero_image',
    ]
  ));
}
add_action('customize_register', 'mytheme_customize_register');