$(document).ready(function() {
	var template = Hogan.compile(typeAhead_tpl.username, { delimiters: '<% %>' });
	$('#user-search').typeahead([
		{
			name: 'username',
			remote: '../admin/search/username/%QUERY.json',
			template: template.render.bind(template)
		}
	]);
});