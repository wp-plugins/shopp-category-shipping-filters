<div class="wrap">
	<h2>Shopp Category Shipping Filters</h2>

	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="action" value="save_shopp_category_shipping_filters" />
		<?php
		$shipping_modules = $this->active_shipping_modules();
		$shopp_category_shipping_filters = $this->get_shipping_filters();
		?>

		<?php foreach($shipping_modules as $module): ?>
			<?php foreach($module as $id => $instance): ?>
			<div class="module-settings">
				<h3><?php echo $instance['label']; ?></h3>
				<table class="form-table">

					<tbody>
						<tr>
							<th scope="row" valign="top">Applicable Categories</th>
							<td>
								<label><input type="checkbox" name="all" value="all" class="all" /> All</label><br/>
								<?php $categories = shopp_product_categories(); ?>
								<?php foreach($categories as $cat): ?>
									<label><input type="checkbox" name="shopp_category_shipping_filters[<?php echo $id; ?>][]" value="<?php echo $cat->id; ?>" <?php if( in_array($cat->id, $shopp_category_shipping_filters[$id]) ) echo 'checked="checked"';?> /> <?php echo $cat->name; ?></label><br/>
								<?php endforeach; ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php endforeach; ?>
		<?php endforeach; ?>
		<input type="submit" class="button-primary" value="Save Filters" />
	</form>
	<script>
	jQuery("input.all").change(function() {
		if(jQuery(this).is(':checked')) {
			jQuery(this).parents("td").first().children("label").children("input").attr('checked','checked');
		} else {
			jQuery(this).parents("td").first().children("label").children("input").removeAttr('checked');
		}
	});
	</script>
</div>