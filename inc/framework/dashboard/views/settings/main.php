<div
	class="kitify-dashboard-settings-page"
	:class="{ 'proccesing-state': proccesingState }"
>
	<div class="kitify-dashboard-settings-page__inner kitify-dashboard-page__panel">
		<div class="subpage-category-list">
			<plugin-settings-toggle
				v-for="( categoryData, index ) in categoryList"
				:key="`category-${ categoryData.slug }`"
				:config="categoryData"
			></plugin-settings-toggle>
		</div>
		<div class="subpage-content">
			<component
				:is="subpage"
			></component>
		</div>
	</div>
</div>
