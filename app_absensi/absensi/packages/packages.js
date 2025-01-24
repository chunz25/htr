function packages(){
	Ext.Loader.setConfig({ 
		enabled	: true, 
		paths	: { 
			ABSENSI	: Settings.BASE_URL + 'app_absensi/absensi',
			Ext		: Settings.url_ext,
		} 		
	});	
	
	Ext.require('ABSENSI.abstract.FieldButton');
	Ext.require('ABSENSI.abstract.SearchField');
	Ext.require('ABSENSI.abstract.Window');

}