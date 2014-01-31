$(document).ready( function() {
	$('#permissions').moveSelect({btn_save: false});

	$('#modal-user_groups').on('clean', function(){
		// reset selected options
		$('#move-select-empty').trigger('click');
	});

	// handle data load
	$('#modal-user_groups').on('load', function(e, data){
		var perms = data.permissions;
		$.each(perms, function(key, val){
			if(key.indexOf('.*') >= 0)
			{
				$('#move-select-base option[value^="'+key.replace('.*', '')+'"]').attr("selected","selected");
			}
			else
				$('#move-select-base option[value="'+key+'"]').attr("selected","selected");
		});
		$('#move-select-in').trigger('click');
	});

	//handle proper data save
	$('#modal-user_groups').on('save', function(){
		modalSubmitData['group[permissions]'] = {};
		$('#move-select-container option').each(function(){
			modalSubmitData['group[permissions]'][$(this).val()] = 1;
		});
	});
});