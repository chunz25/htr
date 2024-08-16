$(document).ready(function(){
	var start = 0;
	var limit = 20;
	load_grid_pegawai(start, limit);
	
	$('#gridpegawai').on('click', '.clickable-row', function(event) {
		var grid = $(this).closest('tr');
		console.log('select');		
		console.log(grid.find("td:eq(2)").text());
		console.log($(this).find("td:eq()"));		
		$(this).addClass('active').siblings().removeClass('active');
	});
	
	$('#id_input_cari').on('keypress', function(e){
		if(e.which === 13){
			console.log('cari');
		}
	});
	
	$('#id_pilih').on('click', function(e){
		e.preventDefault();
		e.stopPropagation();
		
		var grid = $('#gridpegawai').find('.active');
		var grid2 = $('#gridpegawai').find('.active:eq(2)');
		
		// console.log(grid);
		// console.log(grid.text());
	});	
});

function load_grid_pegawai(start, limit){
	$.getJSON(
		SITE_URL+'/home/get_daftar_pegawai', '',
		function(response){
			if(response.success){
				var html = '';
				$.each(response.data, function(index, record){
					html += '<tr class="clickable-row">';
						html += '<td></td>';
						html += '<td>'+record.nip+'</td>';
						html += '<td>'+record.nama+'</td>';
						html += '<td>'+record.jabatan+'</td>';
						html += '<td>'+record.satker+'</td>';
					html += '</tr>';					
				});
				$('#gridpegawai > tbody').html(html);
				if(response.count > 0){
					var visiblePages = Math.ceil(response.count / limit);
					$('#paging_grid').twbsPagination({
						initiateStartPageClick: false,
						totalPages: visiblePages,
						visiblePages: 10,
						startPage: 1,
						onPageClick: function (event, page) {
							var start = (page - 1) * limit;	
							load_grid_content(kategoriid, start, limit);
						}
					});						
				}				
			}
		}
	);
}