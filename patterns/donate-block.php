<?php
/**
 * Title: Donate Block
 * Slug: monitor-custom/donate-block
 * Categories: call-to-action
 * Description: Display donation form (requires TM Donate plugin)
 */

// Only show if TM Donate plugin is active
if ( ! function_exists( 'tm_donate_init' ) && ! class_exists( 'TM_Donate' ) ) {
    return;
}

// Option to hide on TBJP syndicated articles
// Change to true if you want to hide donations on syndicated content
$hide_on_tbjp = false;

if ( $hide_on_tbjp && has_category( 'tbjp' ) ) {
    return;
}
?>

<!-- wp:tm-donate/donate-block -->
<div class="wp-block-tm-donate-donate-block tm-donate-block">
  <div class="tm-donate-form" data-preset-amounts="[5,10,25,50,100]" data-allow-custom="true"
    data-button-text="Donate Now">
    <h3 class="tm-donate-title">Support Tampa Monitor</h3>
    <p class="tm-donate-description">Your donation helps us continue our work. Every contribution makes a
      difference.</p>
    <div class="tm-donate-amounts"><button type="button" class="tm-donate-amount-btn"
        data-amount="5">$5</button><button type="button" class="tm-donate-amount-btn"
        data-amount="10">$10</button><button type="button" class="tm-donate-amount-btn"
        data-amount="25">$25</button><button type="button" class="tm-donate-amount-btn"
        data-amount="50">$50</button><button type="button" class="tm-donate-amount-btn"
        data-amount="100">$100</button></div>
    <div class="tm-donate-custom-amount"><input type="number" placeholder="Custom amount"
        class="tm-donate-custom-input" min="5" step="1" /></div>
    <div class="tm-donate-donor-info"><input type="text" placeholder="Your Name" class="tm-donate-name"
        required /><input type="email" placeholder="Email Address" class="tm-donate-email" required /></div>
    <div class="tm-donate-recurring" style="display:none" data-label="Make my donation monthly"><label
        class="tm-donate-recurring-label"><input type="checkbox" class="tm-donate-recurring-toggle" /><span
          class="tm-donate-recurring-label-text">Make my donation monthly</span></label>
      <p class="tm-donate-recurring-help">Recurring donations use the subscription prices configured in TM Donate
        settings.</p>
      <div class="tm-donate-recurring-options" style="display:none"></div>
    </div><button type="button" class="tm-donate-submit-btn">Donate Now</button>
    <div class="tm-donate-loading" style="display:none">
      <p>Processing your donation...</p>
    </div>
  </div>
</div>
<!-- /wp:tm-donate/donate-block -->
