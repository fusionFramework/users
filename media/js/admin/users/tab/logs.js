$.fn.dataTable.defaults.fnServerParams = function(aoData){
	aoData.push( { "name": "date_start", "value": $('#log-date-start').val() } );
	aoData.push( { "name": "date_end", "value": $('#log-date-end').val() } );
};

$(document).ready(function () {
	$('#dataTable-logs').find('.btn-create').hide();

	$('#log-date-start').datepicker({'format': 'yyyy-mm-dd'}).on('changeDate', function(ev){
		datalogs.fnDraw();
	});
	$('#log-date-end').datepicker({'format': 'yyyy-mm-dd'}).on('changeDate', function(ev){
		datalogs.fnDraw();
	});

	datalogs.on('click', '.btn-action-show', function(e){
		var id = $(this).data('id');
		$('body').modalmanager('loading');
		$('#log-tabs a:first').tab('show');

		datalogs.on('req.success', function(e, resp, s, x){
				var data = resp[0].data;

				$('#log-m-message').html(data.message);
				$('#log-m-alias').html(data.alias);
				$('#log-m-datetime').html(data.time);
				$('#log-m-location').html(data.location);
				$('#log-m-ip').html(data.ip);
				$('#log-m-browser').html(data.browser);
				$('#log-m-params').html(JSON.stringify(data.params, null, "\t"));

				$('#modal-logs').modal({"width": 650, "height": 300});
				$('body').modalmanager('removeLoading');
			})
			.on('req.error', function(e, errors){
				$('body').modalmanager('removeLoading');
				$.each(errors, function(i, v){
					$('.notifications').notify({
						message: { text: v.value },
						type: "danger"
					}).show();
				});
			})
			.req({"url": log_routes.modal.replace(0, id), type: "GET"})
	});
});