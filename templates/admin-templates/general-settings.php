<div class="kitify-settings-page kitify-settings-page__general">

    <div class="cx-vui-title cx-vui-title--divider"><?php _e('Theme Layout', 'kitify'); ?></div>

    <cx-vui-select
      name="single_page_template"
      label="<?php esc_attr_e( 'Default Single Page Template', 'kitify' ); ?>"
      :wrapper-css="[ 'equalwidth' ]"
      size="fullwidth"
      :options-list="pageOptions.single_page_template.options"
      v-model="pageOptions.single_page_template.value">
    </cx-vui-select>

    <cx-vui-select
      name="single_post_template"
      label="<?php esc_attr_e( 'Default Single Post Template', 'kitify' ); ?>"
      :wrapper-css="[ 'equalwidth' ]"
      size="fullwidth"
      :options-list="pageOptions.single_post_template.options"
      v-model="pageOptions.single_post_template.value">
    </cx-vui-select>

    <div class="cx-vui-title cx-vui-title--divider"><?php _e('Taxonomy to show in breadcrumbs for content types', 'kitify'); ?></div>

    <?php
    $post_types = get_post_types(array('public' => true), 'objects');
    $deny_list = array('elementor_library', 'kitify-theme-core');

    if (is_array($post_types) && !empty($post_types)) {

        foreach ($post_types as $post_type) {

            if (in_array($post_type->name, $deny_list)) {
                continue;
            }

            $taxonomies = get_object_taxonomies($post_type->name, 'objects');

            if (is_array($taxonomies) && !empty($taxonomies)) {

                $post_type_name = 'breadcrumbs_taxonomy_' . $post_type->name;
                ?>
            <cx-vui-select
              name="<?php echo $post_type_name; ?>"
              label="<?php echo $post_type->label; ?>"
              :wrapper-css="[ 'equalwidth' ]"
              size="fullwidth"
              :options-list="pageOptions['<?php echo $post_type_name; ?>']['options']"
              v-model="pageOptions['<?php echo $post_type_name; ?>']['value']"
            ></cx-vui-select><?php
            }
        }
    }
    ?>

</div>
