<?php
/**
 * Main dashboard template
 */
?><div
	id="kitify-dashboard-page"
	class="kitify-dashboard-page"
	ref="KitifyDashboardPage"
>

	<div class="kitify-dashboard-page__body">

		<kitify-dashboard-alert-list
			:alert-list="alertNoticeList"
		></kitify-dashboard-alert-list>

		<kitify-dashboard-before-content
			:config="beforeContentConfig"
		></kitify-dashboard-before-content>

		<div class="kitify-dashboard-page__content">

			<kitify-dashboard-header
				:config="headerConfig"
			></kitify-dashboard-header>

			<div class="kitify-dashboard-page__component">

				<kitify-dashboard-before-component
					:config="beforeComponentConfig"
				></kitify-dashboard-before-component>

				<component
					:is="pageModule"
					:subpage="subPageModule"
				></component>

				<kitify-dashboard-after-component
					:config="afterComponentConfig"
				></kitify-dashboard-after-component>

			</div>

		</div>

		<div
			class="kitify-dashboard-page__sidebar-container"
			v-if="sidebarVisible"
		>

			<kitify-dashboard-before-sidebar
				:config="beforeSidebarConfig"
			></kitify-dashboard-before-sidebar>

			<kitify-dashboard-sidebar
				:config="sidebarConfig"
				:guide="guideConfig"
				:help-center="helpCenterConfig"
			></kitify-dashboard-sidebar>

			<kitify-dashboard-after-sidebar
				:config="afterSidebarConfig"
			></kitify-dashboard-after-sidebar>

		</div>

	</div>

	<transition name="popup">
		<cx-vui-popup
			class="service-actions-popup"
			v-model="serviceActionsVisible"
			:footer="false"
			body-width="400px"
		>
			<div slot="title">
				<div class="cx-vui-popup__header-label">Service Actions</div>
			</div>
			<div class="service-actions-popup__form" slot="content">
				<cx-vui-select
					size="fullwidth"
					placeholder="Choose Action"
					:prevent-wrap="true"
					:options-list="serviceActionOptions"
					v-model="serviceAction"
				></cx-vui-select>
				<cx-vui-button
					button-style="accent"
					size="mini"
					:loading="serviceActionProcessed"
					@click="executeServiceAction"
				>
					<span slot="label">Go</span>
				</cx-vui-button>
			</div>
		</cx-vui-popup>
	</transition>

</div>
