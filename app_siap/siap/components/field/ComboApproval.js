Ext.define('SIAP.components.field.ComboApproval', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.comboapproval',
	fieldLabel: '',
	name: 'approval', isLoad: true,
	initComponent	: function() {	
		var me = this;	
		var storemapproval = Ext.create('Ext.data.Store', {
			autoLoad : me.isLoad,
			storeId: 'storemapproval',
			fields: ['id','text','desc'],
			proxy: {
				type: 'ajax',
				url: Settings.MASTER_URL + '/c_approval/getApproval',
				reader: {
					type: 'json',
					root:'data'
				}
			},
		});		
		Ext.apply(me,{		
			store: storemapproval,
			triggerAction : 'all',
			editable : true,
			displayField: "desc",
			valueField: 'id',
			name: me.name,		
		});		
		me.callParent([arguments]);
	},
});