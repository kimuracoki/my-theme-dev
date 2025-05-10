<?php
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
  }
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_assets');