$(document).ready(function() {
	$('#notes-container').slimScroll({height: "300px", start: "bottom"});

	$('#user-note-add').click(function(e){
		e.preventDefault();

		var csrf = $('#form-notes').find('input[name="csrf"]').val();

		$(this)
			.on('req.success', function(e, resp, s, x){
				$('.notifications').notify({
					message:{ text: 'Note added successfully'},
					type:'success',
					fadeOut:{ enabled:true, delay:6000 }
				}).show();

				var data = resp[0].data;

				$('#notes-container').append('<div class="row"><div class="col-lg-10 col-lg-offset-1"><div class="panel panel-'+data.type+'"><div class="panel-heading">By '+data.created_by+' at '+data.created+'</div><div class="panel-body">'+data.content+'</div></div></div></div>');

				//clean out the form and set csrf again
				$('#form-notes')[0].reset();
				$('#form-notes').find('input[name="csrf"]').val(csrf);

				$('#notes-container').slimScroll({scrollTo: $('#notes-container').height()});
			})
			.on('req.error', function(e, resp, s, x){
				var name = $("#input-item_name").val();
				$('.notifications').notify({
					message:{ text:'Note could not be added.'},
					type:'warning',
					fadeOut:{ enabled:true, delay:6000 }
				}).show();
			})
			.req({url: note_routes.submit, type: "POST"}, $('#form-notes'));
	});

	$('.note-refresh').click(function(){
		$(this)
			.on('req.success', function(e, resp, s, x){

				var data = resp[0].data;
				var html = '';

				$.each(data, function(i, v){
					html += '<div class="row"><div class="col-lg-10 col-lg-offset-1"><div class="panel panel-'+v.type+'"><div class="panel-heading">By '+v.created_by+' at '+v.created+'</div><div class="panel-body">'+v.content+'</div></div></div></div>';
				});

				$('#notes-container').html(html);

				$('#notes-container').slimScroll({scrollTo: $('#notes-container').height()});
			})
			.on('req.error', function(e, resp, s, x){
				var name = $("#input-item_name").val();
				$('.notifications').notify({
					message:{ text:'Notes could not be refreshed.'},
					type:'warning',
					fadeOut:{ enabled:true, delay:6000 }
				}).show();
			})
			.req({url: note_routes.refresh, type: "GET"});
	});
});