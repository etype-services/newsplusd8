<?php

namespace Drupal\gavias_blockbuilder\shortcodes;

if (!class_exists('gsc_drupal_block')):
  class gsc_drupal_block {

    public function render_form() {
      $fields = [
        'type' => 'gsc_drupal_block',
        'title' => ('Drupal Block'),
        'size' => 12,

        'fields' => [
          [
            'id' => 'title',
            'type' => 'text',
            'title' => t('Title display Admin'),
            'class' => 'display-admin'
          ],
          [
            'id' => 'block_drupal',
            'type' => 'select',
            'title' => t('Block for drupal'),
            'options' => gavias_blockbuilder_get_blocks_options(),
          ],
          [
            'id' => 'hidden_title',
            'type' => 'select',
            'title' => t('Hidden title'),
            'options' => ['on' => 'Display', 'off' => 'Hidden'],
            'desc' => t('Hidden title default for block')
          ],
          [
            'id' => 'align_title',
            'type' => 'select',
            'title' => t('Align title'),
            'options' => [
              'title-align-left' => 'Align Left',
              'title-align-right' => 'Align Right',
              'title-align-center' => 'Align Center'
            ],
            'std' => 'title-align-center',
            'desc' => t('Align title default for block')
          ],
          [
            'id' => 'remove_margin',
            'type' => 'select',
            'title' => ('Remove Margin'),
            'options' => ['on' => 'Yes', 'off' => 'No'],
            'std' => 'off',
            'desc' => t('Defaut block margin bottom 30px, You can remove margin for block')
          ],
          [
            'id' => 'style_text',
            'type' => 'select',
            'title' => t('Skin Text for box'),
            'options' => [
              'text-dark' => 'Text dark',
              'text-light' => 'Text light',
            ],
            'std' => 'text-dark'
          ],
          [
            'id' => 'el_class',
            'type' => 'text',
            'title' => t('Extra class name'),
            'desc' => t('Style particular content element differently - add a class name and refer to it in custom CSS.'),
          ],
          [
            'id' => 'animate',
            'type' => 'select',
            'title' => t('Animation'),
            'desc' => t('Entrance animation'),
            'options' => gavias_blockbuilder_animate(),
          ],
        ],
      ];
      return $fields;
    }

    public function render_content($item) {
      print self::sc_drupal_block($item['fields']);
    }

    public function sc_drupal_block($attr, $content = NULL) {
      extract(shortcode_atts([
        'title' => '',
        'block_drupal' => '',
        'hidden_title' => 'on',
        'align_title' => 'title-align-center',
        'el_class' => '',
        'style_text' => '',
        'remove_margin' => 'off',
        'animate' => ''
      ], $attr));

      $output = '';
      $class = [];
      $class[] = $align_title;
      $class[] = $el_class;
      $class[] = 'hidden-title-' . $hidden_title;
      $class[] = 'remove-margin-' . $remove_margin;
      $class[] = $style_text;
      if ($animate) {
        $class[] = 'wow';
        $class[] = $animate;
      }
      if ($block_drupal) {
        $output .= '<div class="widget gsc-block-drupal ' . implode(' ', $class,) . '">';
        $output .= gavias_blockbuilder_render_block($block_drupal);
        $output .= '</div>';
      }
      return $output;
    }

    public function load_shortcode() {
      add_shortcode('block', ['gsc_drupal_block', 'sc_drupal_block']);
    }
  }
endif;
   



