<?php

function newsplus_form_system_theme_settings_alter(&$form, &$form_state) {

  $form['#attached']['library'][] = 'newsplus/theme-settings';

  $form['mtt_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('MtT Theme Settings'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  );

  $form['mtt_settings']['tabs'] = array(
    '#type' => 'vertical_tabs',
    '#default_tab' => 'basic_tab',
  );

  $form['mtt_settings']['basic_tab']['basic_settings'] = array(
    '#type' => 'details',
    '#title' => t('Basic Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['breadcrumb_separator'] = array(
    '#type' => 'textfield',
    '#title' => t('Breadcrumb separator'),
    '#default_value' => theme_get_setting('breadcrumb_separator','newsplus'),
    '#size'          => 5,
    '#maxlength'     => 10,
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['header'] = array(
    '#type' => 'item',
    '#markup' => t('<div class="theme-settings-title">Header positioning</div>'),
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['fixed_header'] = array(
    '#type' => 'checkbox',
    '#title' => t('Fixed position'),
    '#description'   => t('Use the checkbox to apply fixed position to the header.'),
    '#default_value' => theme_get_setting('fixed_header', 'newsplus'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['sitename_display'] = array(
    '#type' => 'item',
    '#markup' => t('<div class="theme-settings-title">Site Name display</div>'),
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['display_fixed_site_name'] = array(
    '#type' => 'checkbox',
    '#title' => t('Site Name in fixed Header'),
    '#description'   => t('Use the checkbox to display the Site Name in the fixed Header.'),
    '#default_value' => theme_get_setting('display_fixed_site_name', 'newsplus'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['scrolltop'] = array(
    '#type' => 'item',
    '#markup' => t('<div class="theme-settings-title">Scroll to top</div>'),
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['scrolltop_display'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show scroll-to-top button'),
    '#description'   => t('Use the checkbox to enable or disable scroll-to-top button.'),
    '#default_value' => theme_get_setting('scrolltop_display', 'newsplus'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['layout_tab']['layout'] = array(
    '#type' => 'details',
    '#title' => t('Layout'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['layout_tab']['layout']['three_columns_grid_layout'] = array(
    '#type' => 'select',
    '#title' => t('Adjustments to the three-column, Bootstrap layout grid'),
    '#description'   => t('From the drop down select box, choose the grid of the three-column layout you would like to use. This way, you can set the width of each of your columns, when choosing a three-column layout.
    <br><br>Note: All options refer to Bootstrap columns.'),
    '#default_value' => theme_get_setting('three_columns_grid_layout', 'newsplus'),
    '#options' => array(
    'grid_3_6_3' => t('3-6-3/Default'),
    'grid_2_6_4' => t('2-6-4'),
    'grid_4_6_2' => t('4-6-2'),
    ),
  );

  $form['mtt_settings']['layout_tab']['layout']['pre_header_left_grid_class'] = array(
    '#type' => 'textfield',
    '#title' => t('Pre Header Left Grid Class, ie col-md-6'),
    '#default_value' => theme_get_setting('pre_header_left_grid_class', 'newsplus'),
  );

  $form['mtt_settings']['layout_tab']['layout']['pre_header_right_grid_class'] = array(
    '#type' => 'textfield',
    '#title' => t('Pre Header Right Grid Class, ie col-md-6'),
    '#default_value' => theme_get_setting('pre_header_right_grid_class', 'newsplus'),
  );

  $form['mtt_settings']['layout_tab']['layout']['offset_fullwidth_content_label'] = array(
    '#type' => 'item',
    '#markup' => t('<div class="theme-settings-title">Main content layout</div>'),
  );

  $form['mtt_settings']['layout_tab']['layout']['offset_fullwidth_content'] = array(
    '#type' => 'checkbox',
    '#title' => t('Offset fullwidth Main Content area'),
    '#description'   => t('Use the checkbox to offset the Main Content area in pages with no sidebars.'),
    '#default_value' => theme_get_setting('offset_fullwidth_content', 'newsplus'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['looknfeel_tab']['looknfeel'] = array(
    '#type' => 'details',
    '#title' => t('Look\'n\'Feel'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['looknfeel_tab']['looknfeel']['color_scheme'] = array(
    '#type' => 'select',
    '#title' => t('Color Schemes'),
    '#description'   => t('From the drop-down menu, select the color scheme you prefer.'),
    '#default_value' => theme_get_setting('color_scheme', 'newsplus'),
    '#options' => array(
    'default' => t('Red/default'),
    'blue' => t('Blue'),
    'green' => t('Green'),
    'orange' => t('Orange'),
    'pink' => t('Pink'),
    'purple' => t('Purple'),
    'gray' => t('Gray'),
    'white' => t('White'),
    'light-gray' => t('Light Gray'),
    'solid-red' => t('Solid Red'),
    'solid-blue' => t('Solid Blue'),
    'solid-green' => t('Solid Green'),
    'solid-orange' => t('Solid Orange'),
    'solid-pink' => t('Solid Pink'),
    'solid-purple' => t('Solid Purple'),
    'solid-gray' => t('Solid Gray'),
    'solid-white' => t('Solid White'),
    'solid-light-gray' => t('Solid Light Gray'),
    ),
  );

  $form['mtt_settings']['looknfeel_tab']['looknfeel']['content_background_color'] = array(
    '#type' => 'select',
    '#title' => t('Content wrapper background color'),
    '#description'   => t('From the drop down select box, choose the color for the backgound of the region that wraps the main content area.'),
    '#default_value' => theme_get_setting('content_background_color', 'newsplus'),
    '#options' => array(
      'gray' => t('Gray'),
      'white' => t('White'),
    ),
  );

  $form['mtt_settings']['font_tab']['font'] = array(
    '#type' => 'details',
    '#title' => t('Font Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['font_tab']['font']['font_title'] = array(
    '#type' => 'item',
    '#markup' => 'For every region pick the <strong>font-family</strong> that corresponds to your needs.',
  );

  $form['mtt_settings']['font_tab']['font']['sitename_font_family'] = array(
    '#type' => 'select',
    '#title' => t('Site name'),
    '#default_value' => theme_get_setting('sitename_font_family', 'newsplus'),
    '#options' => array(
      'sff-31' => t('Alegreya SC, Georgia, Times, Times New Roman, Serif'),
      'sff-25' => t('Alegreya, Georgia, Times, Times New Roman, Serif'),
      'sff-40' => t('Archivo Narrow, Arial, Helvetica Neue, Sans-serif'),
      'sff-42' => t('Arimo, Arial, Helvetica Neue, Sans-serif'),
      'sff-19' => t('Cabin, Helvetica Neue, Arial, Sans-serif'),
      'sff-16' => t('Cinzel, Georgia, Times, Serif'),
      'sff-27' => t('Crimson Text, Georgia, Times, Times New Roman, Serif'),
      'sff-22' => t('Droid Serif, Georgia, Times, Times New Roman, Serif'),
      'sff-09' => t('Exo, Arial, Helvetica Neue, Sans-serif'),
      'sff-33' => t('Fira Sans, Arial, Helvetica Neue, Sans-serif'),
      'sff-28' => t('Gentium Book Basic, Georgia, Times, Times New Roman, Serif'),
      'sff-13' => t('Georgia, Times, Serif'),
      'sff-21' => t('Helvetica Neue, Arial, Sans-serif'),
      'sff-12' => t('Josefin Sans, Georgia, Times, Serif'),
      'sff-36' => t('Julius Sans One, Arial, Helvetica Neue, Sans-serif'),
      'sff-07' => t('Lato, Helvetica Neue, Arial, Sans-serif'),
      'sff-34' => t('Lora, Georgia, Times, Times New Roman, Serif'),
      'sff-01' => t('Merriweather, Georgia, Times, Serif'),
      'sff-32' => t('Montserrat SC, Arial, Helvetica Neue, Sans-serif'),
      'sff-20' => t('Noto Sans, Arial, Helvetica Neue, Sans-serif'),
      'sff-26' => t('Noto Serif, Georgia, Times, Times New Roman, Serif'),
      'sff-38' => t('Open Sans Condensed, Arial, Helvetica Neue, Sans-serif'),
      'sff-06' => t('Open Sans, Helvetica Neue, Arial, Sans-serif'),
      'sff-17' => t('Oswald, Helvetica Neue, Arial, Sans-serif'),
      'sff-15' => t('Philosopher, Georgia, Times, Serif'),
      'sff-18' => t('Playfair Display SC, Georgia, Times, Serif'),
      'sff-14' => t('Playfair Display, Times, Serif'),
      'sff-39' => t('PT Sans Narrow, Arial, Helvetica Neue, Sans-serif'),
      'sff-04' => t('PT Sans, Helvetica Neue, Arial, Sans-serif'),
      'sff-23' => t('PT Serif, Georgia, Times, Times New Roman, Serif'),
      'sff-35' => t('Quattrocento Sans, Arial, Helvetica Neue, Sans-serif'),
      'sff-11' => t('Raleway, Helvetica Neue, Arial, Sans-serif'),
      'sff-08' => t('Roboto Condensed, Arial Narrow, Arial, Sans-serif'),
      'sff-10' => t('Roboto Slab, Trebuchet MS, Sans-serif'),
      'sff-05' => t('Roboto, Helvetica Neue, Arial, Sans-serif'),
      'sff-02' => t('Source Sans Pro, Helvetica Neue, Arial, Sans-serif'),
      'sff-30' => t('Times, Times New Roman, Serif'),
      'sff-41' => t('Ubuntu Condensed, Arial, Helvetica Neue, Sans-serif'),
      'sff-03' => t('Ubuntu, Helvetica Neue, Arial, Sans-serif'),
      'sff-29' => t('Volkhov, Georgia, Times, Times New Roman, Serif'),
      'sff-24' => t('Vollkorn, Georgia, Times, Times New Roman, Serif'),
      'sff-37' => t('Work Sans, Arial, Helvetica Neue, Sans-serif'),
    ),
  );

  $form['mtt_settings']['font_tab']['font']['slogan_font_family'] = array(
    '#type' => 'select',
    '#title' => t('Slogan'),
    '#default_value' => theme_get_setting('slogan_font_family', 'newsplus'),
    '#options' => array(
      'slff-31' => t('Alegreya SC, Georgia, Times, Times New Roman, Serif'),
      'slff-25' => t('Alegreya, Georgia, Times, Times New Roman, Serif'),
      'slff-40' => t('Archivo Narrow, Arial, Helvetica Neue, Sans-serif'),
      'slff-42' => t('Arimo, Arial, Helvetica Neue, Sans-serif'),
      'slff-19' => t('Cabin, Helvetica Neue, Arial, Sans-serif'),
      'slff-16' => t('Cinzel, Georgia, Times, Serif'),
      'slff-27' => t('Crimson Text, Georgia, Times, Times New Roman, Serif'),
      'slff-22' => t('Droid Serif, Georgia, Times, Times New Roman, Serif'),
      'slff-09' => t('Exo, Arial, Helvetica Neue, Sans-serif'),
      'slff-33' => t('Fira Sans, Arial, Helvetica Neue, Sans-serif'),
      'slff-28' => t('Gentium Book Basic, Georgia, Times, Times New Roman, Serif'),
      'slff-13' => t('Georgia, Times, Serif'),
      'slff-21' => t('Helvetica Neue, Arial, Sans-serif'),
      'slff-12' => t('Josefin Sans, Georgia, Times, Serif'),
      'slff-36' => t('Julius Sans One, Arial, Helvetica Neue, Sans-serif'),
      'slff-07' => t('Lato, Helvetica Neue, Arial, Sans-serif'),
      'slff-34' => t('Lora, Georgia, Times, Times New Roman, Serif'),
      'slff-01' => t('Merriweather, Georgia, Times, Serif'),
      'slff-32' => t('Montserrat SC, Arial, Helvetica Neue, Sans-serif'),
      'slff-20' => t('Noto Sans, Arial, Helvetica Neue, Sans-serif'),
      'slff-26' => t('Noto Serif, Georgia, Times, Times New Roman, Serif'),
      'slff-38' => t('Open Sans Condensed, Arial, Helvetica Neue, Sans-serif'),
      'slff-06' => t('Open Sans, Helvetica Neue, Arial, Sans-serif'),
      'slff-17' => t('Oswald, Helvetica Neue, Arial, Sans-serif'),
      'slff-15' => t('Philosopher, Georgia, Times, Serif'),
      'slff-18' => t('Playfair Display SC, Georgia, Times, Serif'),
      'slff-14' => t('Playfair Display, Times, Serif'),
      'slff-39' => t('PT Sans Narrow, Arial, Helvetica Neue, Sans-serif'),
      'slff-04' => t('PT Sans, Helvetica Neue, Arial, Sans-serif'),
      'slff-23' => t('PT Serif, Georgia, Times, Times New Roman, Serif'),
      'slff-35' => t('Quattrocento Sans, Arial, Helvetica Neue, Sans-serif'),
      'slff-11' => t('Raleway, Helvetica Neue, Arial, Sans-serif'),
      'slff-08' => t('Roboto Condensed, Arial Narrow, Arial, Sans-serif'),
      'slff-10' => t('Roboto Slab, Trebuchet MS, Sans-serif'),
      'slff-05' => t('Roboto, Helvetica Neue, Arial, Sans-serif'),
      'slff-02' => t('Source Sans Pro, Helvetica Neue, Arial, Sans-serif'),
      'slff-30' => t('Times, Times New Roman, Serif'),
      'slff-41' => t('Ubuntu Condensed, Arial, Helvetica Neue, Sans-serif'),
      'slff-03' => t('Ubuntu, Helvetica Neue, Arial, Sans-serif'),
      'slff-29' => t('Volkhov, Georgia, Times, Times New Roman, Serif'),
      'slff-24' => t('Vollkorn, Georgia, Times, Times New Roman, Serif'),
      'slff-37' => t('Work Sans, Arial, Helvetica Neue, Sans-serif'),
    ),
  );

  $form['mtt_settings']['font_tab']['font']['headings_font_family'] = array(
    '#type' => 'select',
    '#title' => t('Headings'),
    '#default_value' => theme_get_setting('headings_font_family', 'newsplus'),
    '#options' => array(
      'hff-31' => t('Alegreya SC, Georgia, Times, Times New Roman, Serif'),
      'hff-25' => t('Alegreya, Georgia, Times, Times New Roman, Serif'),
      'hff-40' => t('Archivo Narrow, Arial, Helvetica Neue, Sans-serif'),
      'hff-42' => t('Arimo, Arial, Helvetica Neue, Sans-serif'),
      'hff-19' => t('Cabin, Helvetica Neue, Arial, Sans-serif'),
      'hff-16' => t('Cinzel, Georgia, Times, Serif'),
      'hff-27' => t('Crimson Text, Georgia, Times, Times New Roman, Serif'),
      'hff-22' => t('Droid Serif, Georgia, Times, Times New Roman, Serif'),
      'hff-09' => t('Exo, Arial, Helvetica Neue, Sans-serif'),
      'hff-33' => t('Fira Sans, Arial, Helvetica Neue, Sans-serif'),
      'hff-28' => t('Gentium Book Basic, Georgia, Times, Times New Roman, Serif'),
      'hff-13' => t('Georgia, Times, Serif'),
      'hff-21' => t('Helvetica Neue, Arial, Sans-serif'),
      'hff-12' => t('Josefin Sans, Georgia, Times, Serif'),
      'hff-36' => t('Julius Sans One, Arial, Helvetica Neue, Sans-serif'),
      'hff-07' => t('Lato, Helvetica Neue, Arial, Sans-serif'),
      'hff-34' => t('Lora, Georgia, Times, Times New Roman, Serif'),
      'hff-01' => t('Merriweather, Georgia, Times, Serif'),
      'hff-32' => t('Montserrat SC, Arial, Helvetica Neue, Sans-serif'),
      'hff-20' => t('Noto Sans, Arial, Helvetica Neue, Sans-serif'),
      'hff-26' => t('Noto Serif, Georgia, Times, Times New Roman, Serif'),
      'hff-38' => t('Open Sans Condensed, Arial, Helvetica Neue, Sans-serif'),
      'hff-06' => t('Open Sans, Helvetica Neue, Arial, Sans-serif'),
      'hff-17' => t('Oswald, Helvetica Neue, Arial, Sans-serif'),
      'hff-15' => t('Philosopher, Georgia, Times, Serif'),
      'hff-18' => t('Playfair Display SC, Georgia, Times, Serif'),
      'hff-14' => t('Playfair Display, Times, Serif'),
      'hff-39' => t('PT Sans Narrow, Arial, Helvetica Neue, Sans-serif'),
      'hff-04' => t('PT Sans, Helvetica Neue, Arial, Sans-serif'),
      'hff-23' => t('PT Serif, Georgia, Times, Times New Roman, Serif'),
      'hff-35' => t('Quattrocento Sans, Arial, Helvetica Neue, Sans-serif'),
      'hff-11' => t('Raleway, Helvetica Neue, Arial, Sans-serif'),
      'hff-08' => t('Roboto Condensed, Arial Narrow, Arial, Sans-serif'),
      'hff-10' => t('Roboto Slab, Trebuchet MS, Sans-serif'),
      'hff-05' => t('Roboto, Helvetica Neue, Arial, Sans-serif'),
      'hff-02' => t('Source Sans Pro, Helvetica Neue, Arial, Sans-serif'),
      'hff-30' => t('Times, Times New Roman, Serif'),
      'hff-41' => t('Ubuntu Condensed, Arial, Helvetica Neue, Sans-serif'),
      'hff-03' => t('Ubuntu, Helvetica Neue, Arial, Sans-serif'),
      'hff-29' => t('Volkhov, Georgia, Times, Times New Roman, Serif'),
      'hff-24' => t('Vollkorn, Georgia, Times, Times New Roman, Serif'),
      'hff-37' => t('Work Sans, Arial, Helvetica Neue, Sans-serif'),
    ),
  );

  $form['mtt_settings']['font_tab']['font']['paragraph_font_family'] = array(
    '#type' => 'select',
    '#title' => t('Paragraph'),
    '#default_value' => theme_get_setting('paragraph_font_family', 'newsplus'),
    '#options' => array(
      'pff-25' => t('Alegreya, Georgia, Times, Times New Roman, Serif'),
      'pff-40' => t('Archivo Narrow, Arial, Helvetica Neue, Sans-serif'),
      'pff-42' => t('Arimo, Arial, Helvetica Neue, Sans-serif'),
      'pff-19' => t('Cabin, Helvetica Neue, Arial, Sans-serif'),
      'pff-27' => t('Crimson Text, Georgia, Times, Times New Roman, Serif'),
      'pff-22' => t('Droid Serif, Georgia, Times, Times New Roman, Serif'),
      'pff-09' => t('Exo, Arial, Helvetica Neue, Sans-serif'),
      'pff-33' => t('Fira Sans, Arial, Helvetica Neue, Sans-serif'),
      'pff-28' => t('Gentium Book Basic, Georgia, Times, Times New Roman, Serif'),
      'pff-13' => t('Georgia, Times, Serif'),
      'pff-21' => t('Helvetica Neue, Arial, Sans-serif'),
      'pff-12' => t('Josefin Sans, Georgia, Times, Serif'),
      'pff-07' => t('Lato, Helvetica Neue, Arial, Sans-serif'),
      'pff-34' => t('Lora, Georgia, Times, Times New Roman, Serif'),
      'pff-01' => t('Merriweather, Georgia, Times, Serif'),
      'pff-20' => t('Noto Sans, Arial, Helvetica Neue, Sans-serif'),
      'pff-26' => t('Noto Serif, Georgia, Times, Times New Roman, Serif'),
      'pff-38' => t('Open Sans Condensed, Arial, Helvetica Neue, Sans-serif'),
      'pff-06' => t('Open Sans, Helvetica Neue, Arial, Sans-serif'),
      'pff-17' => t('Oswald, Helvetica Neue, Arial, Sans-serif'),
      'pff-15' => t('Philosopher, Georgia, Times, Serif'),
      'pff-18' => t('Playfair Display SC, Georgia, Times, Serif'),
      'pff-14' => t('Playfair Display, Times, Serif'),
      'pff-39' => t('PT Sans Narrow, Arial, Helvetica Neue, Sans-serif'),
      'pff-04' => t('PT Sans, Helvetica Neue, Arial, Sans-serif'),
      'pff-23' => t('PT Serif, Georgia, Times, Times New Roman, Serif'),
      'pff-35' => t('Quattrocento Sans, Arial, Helvetica Neue, Sans-serif'),
      'pff-11' => t('Raleway, Helvetica Neue, Arial, Sans-serif'),
      'pff-08' => t('Roboto Condensed, Arial Narrow, Arial, Sans-serif'),
      'pff-10' => t('Roboto Slab, Trebuchet MS, Sans-serif'),
      'pff-05' => t('Roboto, Helvetica Neue, Arial, Sans-serif'),
      'pff-02' => t('Source Sans Pro, Helvetica Neue, Arial, Sans-serif'),
      'pff-30' => t('Times, Times New Roman, Serif'),
      'pff-41' => t('Ubuntu Condensed, Arial, Helvetica Neue, Sans-serif'),
      'pff-03' => t('Ubuntu, Helvetica Neue, Arial, Sans-serif'),
      'pff-29' => t('Volkhov, Georgia, Times, Times New Roman, Serif'),
      'pff-24' => t('Vollkorn, Georgia, Times, Times New Roman, Serif'),
      'pff-37' => t('Work Sans, Arial, Helvetica Neue, Sans-serif'),
    ),
  );

  $form['mtt_settings']['post_tab']['post'] = array(
    '#type' => 'details',
    '#title' => t('Post'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );


  $form['mtt_settings']['post_tab']['post']['reading_time'] = array(
    '#type' => 'checkbox',
    '#title' => t('Time to read'),
    '#description'   => t('Use the checkbox to enable or disable the "Time to read" indicator.'),
    '#default_value' => theme_get_setting('reading_time', 'newsplus'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['post_tab']['post']['share_links'] = array(
    '#type' => 'checkbox',
    '#title' => t('Share links'),
    '#description'   => t('Use the checkbox to enable or disable the social media sharing functionality'),
    '#default_value' => theme_get_setting('share_links', 'newsplus'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['post_tab']['post']['print_button'] = array(
    '#type' => 'checkbox',
    '#title' => t('Post print'),
    '#description'   => t('Use the checkbox to enable or disable the "Print this page" function.'),
    '#default_value' => theme_get_setting('print_button', 'newsplus'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['post_tab']['post']['font_resize'] = array(
    '#type' => 'checkbox',
    '#title' => t('Font resize'),
    '#description'   => t('Use the checkbox to enable or disable the the function that allows to increase or decrease the font-size.'),
    '#default_value' => theme_get_setting('font_resize', 'newsplus'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['post_tab']['post']['post_progress'] = array(
    '#type' => 'checkbox',
    '#title' => t('Read so far'),
    '#description'   => t('Use the checkbox to enable or disable the reading progress indicator.'),
    '#default_value' => theme_get_setting('post_progress', 'newsplus'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['post_tab']['post']['affix_side'] = array(
    '#type' => 'checkbox',
    '#title' => t('Affixed side'),
    '#description'   => t('Use the checkbox to apply affixed position to the node features sidebar.'),
    '#default_value' => theme_get_setting('affix_side', 'newsplus'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['slideshow_tab']['slideshow'] = array(
    '#type' => 'details',
    '#title' => t('Slideshow'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['slideshow_tab']['slideshow']['slider_revolution_slideshow_boxed'] = array(
    '#type' => 'fieldset',
    '#title' => t('Slideshow - Boxed Width (Slider Revolution)'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['slideshow_tab']['slideshow']['slider_revolution_slideshow_boxed']['slider_revolution_slideshow_boxed_effect'] = array(
    '#type' => 'select',
    '#title' => t('Effects'),
    '#description'   => t('From the drop-down menu, select the slideshow effect you prefer.'),
    '#default_value' => theme_get_setting('slider_revolution_slideshow_boxed_effect', 'newsplus'),
    '#options' => array(
      'notransition' => t('No Transition'),
      'fade' => t('Fade'),
      'crossfade' => t('Cross Fade'),
      'fadethroughdark' => t('Fade through Dark BG'),
      'fadethroughlight' => t('Fade through Light BG'),
      'fadethroughtransparent' => t('Fade through predefined BG'),
      'slideup' => t('Slide To Top'),
      'slidedown' => t('Slide To Bottom'),
      'slideright' => t('Slide To Right'),
      'slideleft' => t('Slide To Left'),
      'slidehorizontal' => t('Slide Horizontal (Next/Previous)'),
      'slidevertical' => t('Slide Vertical (Next/Previous)'),
      'slideoverup' => t('Slide Over To Top'),
      'slideoverdown' => t('Slide Over To Bottom'),
      'slideoverright' => t('Slide Over To Right'),
      'slideoverleft' => t('Slide Over To Left'),
      'slideoverhorizontal' => t('Slide Over Horizontal (Next/Previous)'),
      'slideoververtical' => t('Slide Over Vertical (Next/Previous)'),
      'boxslide' => t('Slide Boxes'),
      'slotslide-horizontal' => t('Slide Slots Horizontal'),
      'slotslide-vertical' => t('Slide Slots Vertical'),
      'boxfade' => t('Fade Boxes'),
      'slotfade-horizontal' => t('Fade Slots Horizontal'),
      'slotfade-vertical' => t('Fade Slots Vertical'),
      'fadefromright' => t('Fade and Slide from Right'),
      'fadefromleft' => t('Fade and Slide from Left'),
      'fadefromtop' => t('Fade and Slide from Top'),
      'fadefrombottom' => t('Fade and Slide from Bottom'),
      'fadetoleftfadefromright' => t('To Left From Right'),
      'fadetorightfadefromleft' => t('To Right From Left'),
      'fadetotopfadefrombottom' => t('To Top From Bottom'),
      'fadetobottomfadefromtop' => t('To Bottom From Top'),
      'parallaxtoright' => t('Parallax to Right'),
      'parallaxtoleft' => t('Parallax to Left'),
      'parallaxtotop' => t('Parallax to Top'),
      'parallaxtobottom' => t('Parallax to Bottom'),
      'parallaxhorizontal' => t('Parallax Horizontal'),
      'parallaxvertical' => t('Parallax Vertical'),
      'slidingoverlayup' => t('Sliding Overlays to the Top'),
      'slidingoverlaydown' => t('Sliding Overlays to the Bottom'),
      'slidingoverlayright' => t('Sliding Overlays to the Right'),
      'slidingoverlayleft' => t('Sliding Overlays to the Left'),
      'slidingoverlayhorizontal' => t('Sliding Overlays Horizontal'),
      'slidingoverlayvertical' => t('Sliding Overlays Vertical'),
      'scaledownfromright' => t('Zoom Out and Fade From Right'),
      'scaledownfromleft' => t('Zoom Out and Fade From Left'),
      'scaledownfromtop' => t('Zoom Out and Fade From Top'),
      'scaledownfrombottom' => t('Zoom Out and Fade From Bottom'),
      'zoomout' => t('ZoomOut'),
      'zoomin' => t('ZoomIn'),
      'slotzoom-horizontal' => t('Zoom Slots Horizontal'),
      'slotzoom-vertical' => t('Zoom Slots Vertical'),
      'curtain-1' => t('Curtain from Left'),
      'curtain-2' => t('Curtain from Right'),
      'curtain-3' => t('Curtain from Middle'),
      '3dcurtain-horizontal' => t('3D Curtain Horizontal'),
      '3dcurtain-vertical' => t('3D Curtain Vertical'),
      'cube' => t('Cube Vertical'),
      'cube-horizontal' => t('Cube Horizontal'),
      'incube' => t('In Cube Vertical'),
      'incube-horizontal' => t('In Cube Horizontal'),
      'turnoff' => t('TurnOff Horizontal'),
      'turnoff-vertical' => t('TurnOff Vertical'),
      'papercut' => t('Paper Cut'),
      'flyin' => t('Fly In'),
      'slideremoveup' => t('Slide Remove To Top'),
      'slideremovedown' => t('Slide Remove To Bottom'),
      'slideremoveright' => t('Slide Remove To Right'),
      'slideremoveleft' => t('Slide Remove To Left'),
      'slideremovehorizontal' => t('Slide Remove Horizontal (Next/Previous)'),
      'slideremovevertical' => t('Slide Remove Vertical (Next/Previous)'),
      'random-selected' => t('Random of Selected'),
      'random-static' => t('Random Flat'),
      'random-premium' => t('Random Premium'),
      'random' => t('Random Flat and Premium'),
    ),
  );

  $form['mtt_settings']['slideshow_tab']['slideshow']['slider_revolution_slideshow_boxed']['slider_revolution_slideshow_boxed_effect_time'] = array(
    '#type' => 'textfield',
    '#title' => t('Effect duration (sec)'),
    '#default_value' => theme_get_setting('slider_revolution_slideshow_boxed_effect_time', 'newsplus'),
    '#description'   => t('Set the speed of animations, in seconds.'),
  );

  $form['mtt_settings']['slideshow_tab']['slideshow']['slider_revolution_slideshow_boxed']['slider_revolution_slideshow_boxed_initial_height'] = array(
    '#type' => 'textfield',
    '#title' => t('Initial Height (px)'),
    '#default_value' => theme_get_setting('slider_revolution_slideshow_boxed_initial_height', 'newsplus'),
    '#description'   => t('Set the initial height, in pixels.'),
  );

  $form['mtt_settings']['slideshow_tab']['slideshow']['main_slideshow'] = array(
    '#type' => 'fieldset',
    '#title' => t('Main Slideshow'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['slideshow_tab']['slideshow']['main_slideshow']['slideshow_effect'] = array(
    '#type' => 'select',
    '#title' => t('Effects'),
    '#description'   => t('From the drop-down menu, select the slideshow effect you prefer.'),
    '#default_value' => theme_get_setting('slideshow_effect', 'newsplus'),
    '#options' => array(
    'fade' => t('fade'),
    'slide' => t('slide'),
    ),
  );

  $form['mtt_settings']['slideshow_tab']['slideshow']['main_slideshow']['slideshow_effect_time'] = array(
    '#type' => 'textfield',
    '#title' => t('Effect duration (sec)'),
    '#default_value' => theme_get_setting('slideshow_effect_time', 'newsplus'),
    '#description'   => t('Set the speed of animations, in seconds.'),
  );

  $form['mtt_settings']['slideshow_tab']['slideshow']['internal_banner'] = array(
    '#type' => 'fieldset',
    '#title' => t('Internal Banner'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['slideshow_tab']['slideshow']['internal_banner']['internal_banner_effect'] = array(
    '#type' => 'select',
    '#title' => t('Effects'),
    '#description'   => t('From the drop-down menu, select the slideshow effect you prefer.'),
    '#default_value' => theme_get_setting('internal_banner_effect', 'newsplus'),
    '#options' => array(
    'fade' => t('fade'),
    'slide' => t('slide'),
    ),
  );

  $form['mtt_settings']['slideshow_tab']['slideshow']['breaking_slideshow'] = array(
    '#type' => 'fieldset',
    '#title' => t('Breaking News Slideshow'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['slideshow_tab']['slideshow']['breaking_slideshow']['breaking_effect'] = array(
    '#type' => 'select',
    '#title' => t('Effects'),
    '#description'   => t('From the drop-down menu, select the slideshow effect you prefer.'),
    '#default_value' => theme_get_setting('breaking_effect', 'newsplus'),
    '#options' => array(
    'fade' => t('fade'),
    'slide' => t('slide'),
    ),
  );

  $form['mtt_settings']['slideshow_tab']['slideshow']['breaking_slideshow']['breaking_effect_time'] = array(
    '#type' => 'textfield',
    '#title' => t('Effect duration (sec)'),
    '#default_value' => theme_get_setting('breaking_effect_time', 'newsplus'),
    '#description'   => t('Set the speed of animations, in seconds.'),
  );

  
  $form['mtt_settings']['google_maps_tab'] = array(
    '#type' => 'details',
    '#title' => t('Google Maps Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['google_maps_tab']['google_maps_key'] = array(
    '#type' => 'textfield',
    '#title' => t('Google Maps API Key'),
    '#description'   => t('Google requires an API key to be included to all calls to Google Maps API. Please create an API key and populate the above field.'),
    '#default_value' => theme_get_setting('google_maps_key','newsplus'),
    '#size'          => 50,
    '#maxlength'     => 50,
  );

}
