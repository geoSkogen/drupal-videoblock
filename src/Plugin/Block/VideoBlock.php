<?php

namespace Drupal\videoblock\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
/**
 * Provides a 'videoblock' Block.
 *
 * @Block(
 *   id = "videoblock_block",
 *   admin_label = @Translation("videoblock Block"),
 *   category = @Translation("customs"),
 * )
 */
class VideoBlock extends BlockBase {

  public function build() {
    $config = $this->getConfiguration();

    if (!empty($config['videoblock_block_src'])) {
      $src = $config['videoblock_block_src'];
      $cta = $config['videoblock_block_cta'];

      $cta_markup = empty($cta) ? '' :
      '<div class="videoblock-cta-wrapper">
        <div class="videoblock-cta-shell">
          <h3 class="videoblock-cta">@cta</h3>
        </div>
      </div>';
    }
    else {
      $src = $this->t('#');
      $cta = $this->t('@');
      $cta_markup = '';
    }
    //
    $attach = [
      '#markup' => $this->t(
        '<section class="videoblock-module-block">
           <div class="videoblock-video-wrapper">
             <div class="videoblock-video-shell">
               <video
                 src="@src"
                 muted="true"
                 loop="true"
                 autoplay="true"
                 controls
                 class="videoblock-video"
               >
               </video>
             </div>
           </div>'
           . $cta_markup .
         '</section> ',
        [ '@src' => $src, '@cta' => $cta ]
      )
    ];
    $attach['#theme'] = 'videoblock';
    $attach['#attached'] = [ 'library'=> ['videoblock/videoblock'] ];
    return $attach;
  }


  public function blockForm($form, FormStateInterface $form_state) {

   $form = parent::blockForm($form, $form_state);
   $config = $this->getConfiguration();

   $form['videoblock_block_src'] = [
     '#type' => 'textfield',
     '#title' => $this->t('URL'),
     '#description' => $this->t('insert the URL of your video file'),
     '#default_value' => $config['videoblock_block_src'] ?? '',
   ];

   $form['videoblock_block_cta'] = [
     '#type' => 'textfield',
     '#title' => $this->t('CTA'),
     '#description' => $this->t('add a CTA for the video block - optional'),
     '#default_value' => $config['videoblock_block_cta'] ?? '',
   ];

   return $form;
 }


 public function blockSubmit($form, FormStateInterface $form_state) {
   parent::blockSubmit($form, $form_state);
   $values = $form_state->getValues();
   $this->configuration['videoblock_block_src'] = $values['videoblock_block_src'];
   $this->configuration['videoblock_block_cta'] = $values['videoblock_block_cta'];
 }



 public function blockValidate($form, FormStateInterface $form_state) {
   if($form_state->getValue('videoblock_block_src') === ''){
     $form_state->setErrorBySrc('videoblock_block_src', $this->t('Field cannot be blank'));
   }
 }

}
