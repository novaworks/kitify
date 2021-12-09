<div class="kitify-settings-page kitify-settings-page__fonts-manager">
    <div class="cx-vui-title cx-vui-title--divider" v-html="'<?php _e('Custom Fonts', 'kitify'); ?>'"></div>
    <kitify-fonts-manager :value="fieldsList" @input="onInput"></kitify-fonts-manager>
    <br/>
    <cx-vui-button
      :button-style="'accent'"
      :loading="savingStatus"
      @click="saveOptions"
    >
        <svg slot="label" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.6667 5.33333V1.79167H1.79167V5.33333H10.6667ZM6.125 13.4167C6.65278 13.9444 7.27778 14.2083 8 14.2083C8.72222 14.2083 9.34722 13.9444 9.875 13.4167C10.4028 12.8889 10.6667 12.2639 10.6667 11.5417C10.6667 10.8194 10.4028 10.1944 9.875 9.66667C9.34722 9.13889 8.72222 8.875 8 8.875C7.27778 8.875 6.65278 9.13889 6.125 9.66667C5.59722 10.1944 5.33333 10.8194 5.33333 11.5417C5.33333 12.2639 5.59722 12.8889 6.125 13.4167ZM12.4583 0L16 3.54167V14.2083C16 14.6806 15.8194 15.0972 15.4583 15.4583C15.0972 15.8194 14.6806 16 14.2083 16H1.79167C1.29167 16 0.861111 15.8194 0.5 15.4583C0.166667 15.0972 0 14.6806 0 14.2083V1.79167C0 1.31944 0.166667 0.902778 0.5 0.541667C0.861111 0.180556 1.29167 0 1.79167 0H12.4583Z" fill="currentColor"/></svg>
        <span slot="label"><?php _e('Save Changes', 'kitify'); ?></span>
    </cx-vui-button>
</div>