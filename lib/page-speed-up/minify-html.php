<?php //HTML縮小化用
/**
 * Cocoon WordPress Theme
 * @author: yhira
 * @link: https://wp-cocoon.com/
 * @license: http://www.gnu.org/licenses/gpl-2.0.html GPL v2 or later
 */

//HTMLソースコードの縮小化
if ( !function_exists( 'code_minify_call_back' ) ):
function code_minify_call_back($buffer) {
  if (is_admin()) {
    return $buffer;
  }
  //何故かa開始タグがpタグでラップされるのを修正
  $buffer = preg_replace('{<p>(<a[^>]+?>)</p>}i', "$1", $buffer);
  //何故かa終了タグがpタグでラップされるのを修正
  $buffer = preg_replace('{<p>(</a>)</p>}i', "$1", $buffer);

  //HTMLの縮小化
  if (is_html_minify_enable()) {
    $buffer = minify_html($buffer);
  }

  ///////////////////////////////////////////
  // Lazy Load
  ///////////////////////////////////////////
  if (is_lazy_load_enable()) {
    $buffer = convert_lazy_load_image_tag($buffer);
  }

  //「Warning: Attribute aria-required is unnecessary for elements that have attribute required.」対策
  $buffer = str_replace('aria-required="true" required>', 'aria-required="true">', $buffer);
  $buffer = str_replace('aria-required="true" required="required">', 'aria-required="true">', $buffer);

  ///////////////////////////////////////
  // HTML5エラー除外
  ///////////////////////////////////////
  //Alt属性がないIMGタグにalt=""を追加する
  $buffer = preg_replace('/<img((?![^>]*alt=)[^>]*)>/i', '<img alt=""${1}>', $buffer);
  //画像タグの border="0"を削除する
  $buffer = str_replace(' border="0"', '', $buffer);

  //wpForoのHTML5エラー
  if (is_wpforo_exist()) {
    $buffer = str_replace(' id="wpf-widget-recent-replies"', '', $buffer);
  }
  //BuddyPressのHTML5エラー
  if (is_buddypress_exist()) {
    $buffer = str_replace('<label for="bp-login-widget-rememberme">', '<label>', $buffer);
  }

  //JavaScriptの縮小化
  if (is_amp() && is_js_minify_enable()) {
    $pattern = '{<script[^>]*?>(.*?)</script>}is';
    $subject = $buffer;
    $res = preg_match_all($pattern, $subject, $m);
    //_v($m);
    if ($res && isset($m[1])) {
      foreach ($m[1] as $match) {
        if (empty($match)) {
          continue;
        }
        //_v($match);
        $buffer = str_replace($match, minify_js($match), $buffer);
      }
    }
  }
  //_v($buffer);
  return apply_filters('code_minify_call_back', $buffer);
}
endif;

//最終HTML取得開始
add_action('after_setup_theme', 'code_minify_buffer_start', 99999999);
if ( !function_exists( 'code_minify_buffer_start' ) ):
function code_minify_buffer_start() {
  if (is_admin()) return;
  if (is_server_request_post()) return;
  if (is_server_request_uri_backup_download_php()) return;

  ob_start('code_minify_call_back');
}

endif;
//最終HTML取得終了
add_action('shutdown', 'code_minify_buffer_end');
if ( !function_exists( 'code_minify_buffer_end' ) ):
function code_minify_buffer_end() {
  if (is_admin()) return;
  if (is_server_request_post()) return;
  if (is_server_request_uri_backup_download_php()) return;

  if (ob_get_length()){
    ob_end_flush();
  }
}
endif;


///////////////////////////////////////
// 出力フィルタリングフック
///////////////////////////////////////

// wp_head 出力フィルタリング・ハンドラ追加
add_action( 'wp_head', 'wp_head_buffer_start', 1 );
add_action( 'wp_head', 'wp_head_buffer_end', 99999999 );
// wp_footer 出力フィルタリング・ハンドラ追加
add_action( 'wp_footer', 'wp_footer_buffer_start', 1 );
add_action( 'wp_footer', 'wp_footer_buffer_end', 99999999 );


///////////////////////////////////////
// バッファリング開始
///////////////////////////////////////
if ( !function_exists( 'wp_head_buffer_start' ) ):
function wp_head_buffer_start() {
  ob_start( 'wp_head_minify' );
}
endif;
if ( !function_exists( 'wp_footer_buffer_start' ) ):
function wp_footer_buffer_start() {
  ob_start( 'wp_footer_minify' );
}
endif;

///////////////////////////////////////
// バッファリング終了
///////////////////////////////////////
if ( !function_exists( 'wp_head_buffer_end' ) ):
function wp_head_buffer_end() {
  if (ob_get_length()) ob_end_flush();
}
endif;
if ( !function_exists( 'wp_footer_buffer_end' ) ):
function wp_footer_buffer_end() {
  if (ob_get_length())  ob_end_flush();
}
endif;


///////////////////////////////////////
// フィルター
///////////////////////////////////////
if ( !function_exists( 'wp_head_minify' ) ):
function wp_head_minify($buffer) {

  //ヘッダーコードのCSS縮小化
  if (is_css_minify_enable()) {
    $buffer = tag_code_to_minify_css($buffer);
  }

  //ヘッダーコードのJS縮小化
  if (is_js_minify_enable()) {
    $buffer = tag_code_to_minify_js($buffer);
  }
  //Wordpressが出力する type='text/javascript'を削除
  $buffer = str_replace(" type='text/javascript'", '', $buffer);
  $buffer = str_replace(' type="text/javascript"', '', $buffer);
  //Wordpressが出力する type='text/css'を削除
  $buffer = str_replace(" type='text/css'", '', $buffer);
  $buffer = str_replace(' type="text/css"', '', $buffer);

  //_v($buffer);
  return apply_filters('wp_head_minify', $buffer);
}
endif;

if ( !function_exists( 'wp_footer_minify' ) ):
function wp_footer_minify($buffer) {
  //_v($buffer);
  //フッターコードのCSS縮小化
  if (is_css_minify_enable()) {
    $buffer = tag_code_to_minify_css($buffer);
  }

  //フッターコードのJS縮小化
  if (is_js_minify_enable()) {
    $buffer = tag_code_to_minify_js($buffer);
  }

  //Wordpressが出力する type='text/javascript'を削除
  $buffer = str_replace(" type='text/javascript'", '', $buffer);
  $buffer = str_replace(' type="text/javascript"', '', $buffer);

  //_v($buffer);
  return apply_filters('wp_footer_minify', $buffer);
}
endif;

if ( !function_exists( 'has_match_list_text' ) ):
function has_match_list_text($text, $list){
  //除外リストにマッチするCSS URLは縮小化しない
  $excludes = list_text_to_array($list);
  foreach ($excludes as $exclude_str) {
    if (strpos($text, $exclude_str) !== false) {
      return true;
    }
  }
}
endif;

//imgタグをLazy Load用の画像タグに変換
if ( !function_exists( 'convert_lazy_load_image_tag' ) ):
function convert_lazy_load_image_tag($the_content){
  //AMPページでは実行しない
  if (is_amp()) {
    return $the_content;
  }

  //imgタグをamp-imgタグに変更する
  $res = preg_match_all('/<img(.+?)\/?>/is', $the_content, $m);
  if ($res) {//画像タグがある場合
    //_v($m);
    foreach ($m[0] as $match) {
      //変数の初期化
      $src_attr = null;
      $url = null;
      //var_dump(htmlspecialchars($match));
      $tag = $match;

      $search = '{src=["\'](.+?)["\']}i';
      $replace = 'data-src="$1"';
      $tag = preg_replace($search, $replace, $tag);


      if (preg_match('/class=/i', $tag)) {
        $search = '{class=["\'](.+?)["\']}i';
        $replace = 'class="$1 lozad"';
        $tag = preg_replace($search, $replace, $tag);
      } else {
        $search = '<img';
        $replace = '<img class="lozad"';
        $tag = str_replace($search, $replace, $tag);
      }
      //noscriptタグの追加
      $tag = $tag.'<noscript>'.$match.'</noscript>';

      //imgタグをLazy Load対応に置換
      $the_content = preg_replace('{'.preg_quote($match).'}', $tag , $the_content, 1);
    }
  }
  return $the_content;
}
endif;
