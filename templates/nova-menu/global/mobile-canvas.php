<?php
$settings = $this->get_settings();
?>
<div class="site-canvas-menu off-canvas position-left" id="MenuOffCanvas" data-off-canvas data-transition="overlap">
    <div class="row has-scrollbar">
      <div class="header-mobiles-primary-menu">
        <?php
          wp_nav_menu(array(
            'menu'              => $settings['nova_nav_menu'],
            'container'         => false,
            'menu_class'        => 'vertical menu drilldown mobile-menu',
            'items_wrap'        => '<ul id="%1$s" class="%2$s" data-drilldown data-auto-height="true" data-animate-height="true" data-parent-link="true">%3$s</ul>',
            'link_before'       => '<span>',
            'link_after'        => '</span>',
            'fallback_cb'     	=> '',
            'walker'            => new Foundation_Drilldown_Menu_Walker(),
          ));
        ?>
        <button class="close-button" aria-label="Close menu" type="button" data-close>
          <svg class="nova-close-canvas">
            <use xlink:href="#nova-close-canvas"></use>
          </svg>
        </button>
      </div>
    </div>
</div>
