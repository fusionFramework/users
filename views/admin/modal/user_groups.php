<form id="form-<?=$resource;?>">
	<input type="hidden" name="csrf" value="<?=Security::token()?>" />
			<?php
			echo $model->get_form(['name'])
				->render('bootstrap/form_fields');
			?>

	<div class="form-group">
		<label for="move-select-container" class="col-sm-3 control-label">Permissions</label>

		<div class="col-sm-8" id="permissions">
			<div class="row"><div class="col-sm-12"><a href="#" class="btn btn-xs btn-success pull-right" id="move-select-fill">Move all</a></div></div>
			<div class="row">
				<div class="col-sm-12"><input type="text" id="move-select-filter-base" class="col-sm-12"/></div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<select multiple="multiple" class="col-sm-12 form-control" id="move-select-base" size="8">
						<?php
						$options = Permissions::instance()->all();
						$options[] = 'admin';

						foreach($options as $key => $perm):
							?>
							<option value="<?=$perm?>"><?=$perm?></option>
						<?
						endforeach;
						?>
					</select>
				</div>
				</div>
			<div class="row">
				<div class="col-sm-12 text-center">
					<a class="btn btn-sm btn-primary" id="move-select-in"><i class="fa fa-chevron-circle-down"></i></a>
					<a class="btn btn-sm btn-primary" id="move-select-out"><i class="fa fa-chevron-circle-up"></i></a>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12"><input type="text" class="col-sm-12 pull-right" id="move-select-filter-container" /></div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<select multiple="multiple" class="col-sm-12 form-control" id="move-select-container" size="8" name="group[permissions][]">

					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12"><a href="#" class="btn btn-xs btn-danger pull-right" id="move-select-empty">Remove all</a></div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
</form>