<div class="metabox-holder">

<!-- 目次 -->
<div id="toc" class="postbox">
  <h2 class="hndle"><?php _e( '目次設定', THEME_NAME ) ?></h2>
  <div class="inside">

    <p><?php _e( 'Table of contentsライクな目次設定です。', THEME_NAME ) ?></p>

    <table class="form-table">
      <tbody>
        <!-- プレビュー画面 -->
        <tr>
          <th scope="row">
            <label><?php _e( 'プレビュー', THEME_NAME ) ?></label>
          </th>
          <td>
            <div class="demo toc" style="height: 300px;overflow: auto;">
              <article class="article">
                <?php query_posts('posts_per_page=1&orderby=rand&no_found_rows=1'); ?>
                <?php get_template_part('tmp/content') ?>
                <?php wp_reset_query(); ?>
              </article>
            </div>
            <?php generate_tips_tag(__( 'デモの記事はランダムです。H2見出しがない本文には目次は表示されません。', THEME_NAME )); ?>
          </td>
        </tr>

        <!-- 目次の表示 -->
        <tr>
          <th scope="row">
            <?php generate_label_tag(OP_TOC_VISIBLE, __('目次の表示', THEME_NAME) ); ?>
          </th>
          <td>
            <?php
            generate_checkbox_tag(OP_TOC_VISIBLE , is_toc_visible(), __( '目次を表示する', THEME_NAME ));
            generate_tips_tag(__( '投稿・固定ページの内容から目次を自動付加します。', THEME_NAME ));
            ?>
          </td>
        </tr>

        <!-- 表示ページ -->
        <tr>
          <th scope="row">
            <?php generate_label_tag('', __('表示ページ', THEME_NAME) ); ?>
          </th>
          <td>
            <?php
            generate_checkbox_tag(OP_SINGLE_TOC_VISIBLE , is_single_toc_visible(), __( '投稿ページ', THEME_NAME ));
            echo '<br>';
            generate_checkbox_tag(OP_PAGE_TOC_VISIBLE , is_page_toc_visible(), __( '固定ページ', THEME_NAME ));
            generate_tips_tag(__( '上記のページの目次表示を切り替えることができます。※それ以外のページでは表示されません。', THEME_NAME ));
            ?>
          </td>
        </tr>

        <!-- 目次タイトル -->
        <tr>
          <th scope="row">
            <?php generate_label_tag(OP_TOC_TITLE, __('目次タイトル', THEME_NAME) ); ?>
          </th>
          <td>
            <?php
            generate_textbox_tag(OP_TOC_TITLE, get_toc_title(), __( '目次', THEME_NAME ));
            generate_tips_tag(__( '目次の上にラベル表示されるタイトルを入力してください。', THEME_NAME ));
            ?>
          </td>
        </tr>

        <!-- 生地の切り替え -->
        <tr>
          <th scope="row">
            <?php generate_label_tag(OP_TOC_TOGGLE_SWITCH_ENABLE, __('目次切り替え', THEME_NAME) ); ?>
          </th>
          <td>
            <?php
            generate_checkbox_tag(OP_TOC_TOGGLE_SWITCH_ENABLE , is_toc_toggle_switch_enable(), __( '目次の表示切替機能を有効にする', THEME_NAME ));
            generate_tips_tag(__( '目次内容の表示を切り替えるスイッチング機能を有効にするか。', THEME_NAME ));
            ?>
            <div class="indent">
              <?php
              _e( '開：', THEME_NAME );
              generate_textbox_tag(OP_TOC_OPEN_CAPTION, get_toc_open_caption(), __( '開く', THEME_NAME ), 10);
              echo '<br>';

              _e( '閉：', THEME_NAME );
              generate_textbox_tag(OP_TOC_CLOSE_CAPTION, get_toc_close_caption(), __( '閉じる', THEME_NAME ), 10);
              generate_tips_tag(__( '目次を「開く」「閉じる」のキャプションを変更します。', THEME_NAME ));


              generate_checkbox_tag(OP_TOC_CONTENT_VISIBLE , is_toc_content_visible(), __( '最初から目次内容を表示する', THEME_NAME ));
              generate_tips_tag(__( 'ページ読み込み時に内容を表示した状態にするか。', THEME_NAME ));
              ?>
            </div>
          </td>
        </tr>

        <!-- 表示条件（数） -->
        <tr>
          <th scope="row">
            <?php generate_label_tag(OP_TOC_DISPLAY_COUNT, __('表示条件', THEME_NAME) ); ?>
          </th>
          <td>
            <?php
            $options = array();
            for ($i=2; $i <= 10; $i++) {
              $options[$i] = $i;
            }
            generate_selectbox_tag(OP_TOC_DISPLAY_COUNT, $options, get_toc_display_count());
            _e( 'つ以上見出しがあるとき', THEME_NAME );
            generate_tips_tag(__( '設定した数以上の目次数がある時のみ表示されます。', THEME_NAME ));
            ?>
          </td>
        </tr>

        <!-- 目次表示の深さ -->
        <tr>
          <th scope="row">
            <?php generate_label_tag(OP_TOC_DEPTH, __('目次表示の深さ', THEME_NAME) ); ?>
          </th>
          <td>
            <?php
            $options = array(
              '2' => __( 'H2見出しまで', THEME_NAME ),
              '3' => __( 'H3見出しまで', THEME_NAME ),
              '4' => __( 'H4見出しまで', THEME_NAME ),
              '5' => __( 'H5見出しまで', THEME_NAME ),
              '0' => __( 'H6見出しまで（デフォルト）', THEME_NAME ),
            );
            generate_selectbox_tag(OP_TOC_DEPTH, $options, get_toc_depth());
            generate_tips_tag(__( 'どの見出しの深さまで表示するかを設定します。', THEME_NAME ));
            ?>
          </td>
        </tr>

        <!-- 目次ナンバーの表示 -->
        <tr>
          <th scope="row">
            <?php generate_label_tag(OP_TOC_NUMBER_TYPE, __('目次ナンバーの表示', THEME_NAME) ); ?>
          </th>
          <td>
            <?php
            $options = array(
              'none' => __( '表示しない', THEME_NAME ),
              'number' => __( '数字（デフォルト）', THEME_NAME ),
              'number_detail' => __( '数字詳細（ex: 1.1.1）', THEME_NAME ),
            );
            generate_selectbox_tag(OP_TOC_NUMBER_TYPE, $options, get_toc_number_type());
            generate_tips_tag(__( '設定項目手前の数字の表示形式を設定します。', THEME_NAME ));
            ?>
          </td>
        </tr>

        <!-- 目次の表示順 -->
        <tr>
          <th scope="row">
            <?php generate_label_tag(OP_TOC_BEFORE_ADS, __('目次の表示順', THEME_NAME) ); ?>
          </th>
          <td>
            <?php
            generate_checkbox_tag(OP_TOC_BEFORE_ADS , is_toc_before_ads(), __( '広告の手前に目次を表示する', THEME_NAME ));
            generate_tips_tag(__( '広告やウィジェットの手前に目次を表示します。※最初のH2見出し手前に表示されているとき', THEME_NAME ));
            ?>
          </td>
        </tr>


      </tbody>
    </table>

  </div>
</div>

</div><!-- /.metabox-holder -->