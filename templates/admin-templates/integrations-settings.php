<div class="kitify-settings-page kitify-settings-page__integratios">
  <div class="cx-vui-subtitle cx-vui-subtitle--divider" v-html="'<?php _e('General', 'kitify'); ?>'"></div>
  <cx-vui-switcher name="svg-uploads"
    label="<?php _e('SVG images upload status', 'kitify'); ?>"
    description="<?php _e('Enable or disable SVG images uploading', 'kitify'); ?>"
    :wrapper-css="[ 'equalwidth' ]"
    return-true="enabled"
    return-false="disabled"
    v-model="pageOptions['svg-uploads'].value">
  </cx-vui-switcher>
  <div class="cx-vui-subtitle cx-vui-subtitle--divider" v-html="'<?php _e('Google Maps', 'kitify'); ?>'"></div>

  <cx-vui-input
    name="google-map-api-key"
    label="<?php _e('Google Map API Key', 'kitify'); ?>"
    description="<?php
    echo sprintf(esc_html__('Create own API key, more info %1$s', 'kitify'),
        htmlspecialchars('<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">here</a>', ENT_QUOTES)
    );
    ?>"
    :wrapper-css="[ 'equalwidth' ]"
    size="fullwidth"
    v-model="pageOptions.gmap_api_key.value"></cx-vui-input>

    <cx-vui-input
      name="google-map-backemd-api-key"
      label="<?php _e('Google Map API Key (Backend)', 'kitify'); ?>"
      description="<?php
      echo sprintf(esc_html__('Create own API key, more info %1$s', 'kitify'),
          htmlspecialchars('<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">here</a>', ENT_QUOTES)
      );
      ?>"
      :wrapper-css="[ 'equalwidth' ]"
      size="fullwidth"
      v-model="pageOptions.gmap_backend_api_key.value"></cx-vui-input>

  <cx-vui-switcher
    name="google-map-disable-api-js"
    label="<?php _e('Disable Google Maps API JS file', 'kitify'); ?>"
    description="<?php _e('Disable Google Maps API JS file, if it already included by another plugin or theme', 'kitify'); ?>"
    :wrapper-css="[ 'equalwidth' ]"
    return-true="true"
    return-false="false"
    v-model="pageOptions.disable_gmap_api_js.value">
  </cx-vui-switcher>

  <div
    class="cx-vui-subtitle cx-vui-subtitle--divider"
    v-html="'<?php _e('MailChimp', 'kitify'); ?>'"></div>

  <cx-vui-input
    name="mailchimp-api-key"
    label="<?php _e('MailChimp API key', 'kitify'); ?>"
    description="<?php
    echo sprintf(esc_html__('Input your MailChimp API key %1$s', 'kitify'),
        htmlspecialchars('<a href="http://kb.mailchimp.com/integrations/api-integrations/about-api-keys" target="_blank">About API Keys</a>', ENT_QUOTES)
    );
    ?>"
    :wrapper-css="[ 'equalwidth' ]"
    size="fullwidth"
    v-model="pageOptions['mailchimp-api-key'].value"></cx-vui-input>

  <cx-vui-input
    name="mailchimp-list-id"
    label="<?php _e('MailChimp list ID', 'kitify'); ?>"
    description="<?php
    echo sprintf(esc_html__('Input MailChimp list ID %1$s', 'kitify'),
        htmlspecialchars('<a href="https://mailchimp.com/help/find-audience-id/" target="_blank">About Mailchimp List Keys</a>', ENT_QUOTES)
    ); ?>"
    :wrapper-css="[ 'equalwidth' ]"
    size="fullwidth"
    v-model="pageOptions['mailchimp-list-id'].value"></cx-vui-input>

  <cx-vui-switcher
    name="mailchimp-double-opt-in"
    label="<?php _e('Double opt-in', 'kitify'); ?>"
    description="<?php _e('Send contacts an opt-in confirmation email when they subscribe to your list.', 'kitify'); ?>"
    :wrapper-css="[ 'equalwidth' ]"
    return-true="true"
    return-false="false"
    v-model="pageOptions['mailchimp-double-opt-in'].value">
  </cx-vui-switcher>

  <div
    class="cx-vui-subtitle cx-vui-subtitle--divider"
    v-html="'<?php _e('Instagram', 'kitify'); ?>'"></div>

  <cx-vui-input
    name="insta-access-token"
    label="<?php _e('Access Token', 'kitify'); ?>"
    description="<?php
    echo sprintf(esc_html__('Read more about how to get Instagram Access Token %1$s', 'kitify'),
        htmlspecialchars('<a href="https://nova-works.gitbook.io/kitify/api/how-to-get-instagram-access-token" target="_blank">here</a>', ENT_QUOTES)
    ); ?>"
    :wrapper-css="[ 'equalwidth' ]"
    size="fullwidth"
    v-model="pageOptions.insta_access_token.value"></cx-vui-input>

  <cx-vui-input
    name="insta-business-access-token"
    label="<?php _e('Business Access Token', 'kitify'); ?>"
    description="<?php
    echo sprintf(esc_html__('Read more about how to get Business Instagram Access Token %1$s', 'kitify'),
        htmlspecialchars('<a href="https://nova-works.gitbook.io/kitify/api/how-to-get-instagram-access-token" target="_blank">here</a>', ENT_QUOTES)
    ); ?>"
    :wrapper-css="[ 'equalwidth' ]"
    size="fullwidth"
    v-model="pageOptions.insta_business_access_token.value"></cx-vui-input>

  <cx-vui-input
    name="insta-business-user-id"
    label="<?php _e('Business User ID', 'kitify'); ?>"
    description="<?php
    echo sprintf(esc_html__('Read more about how to get Business User ID %1$s', 'kitify'),
        htmlspecialchars('<a href="https://nova-works.gitbook.io/kitify/api/how-to-get-instagram-access-token" target="_blank">here</a>', ENT_QUOTES)
    ); ?>"
    :wrapper-css="[ 'equalwidth' ]"
    size="fullwidth"
    v-model="pageOptions.insta_business_user_id.value"></cx-vui-input>

</div>
