$(document).ready(function() {
	$('#tab-avatars').moveSelect({prefix: "#avatar-select-", btn_save: $('#avatar-save')});
	$('#avatar-save').click(function(e){
		e.preventDefault();
	});

	$('#tab-avatars').on('save', function(e){
		console.log($('#avatar-save').data('csrf'));
		$(this).on('req.success', function(e, res){
				$('.notifications').notify({
					message: { text: res[0].value },
					type: 'success',
					fadeOut: { enabled: true, delay: 5000 }
				}).show();
			})
			.on('req.error', function(e, err){
				$.each(er, function(i, v){
					$('.notifications').notify({
						message: { text: v.value },
						type: "danger"
					}).show();
				});
			})
			.req({"url": $('#avatar-save').attr('href'), "type": "POST"}, $('#form-avatars'));
	});
});