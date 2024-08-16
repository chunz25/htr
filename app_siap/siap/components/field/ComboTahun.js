Ext.define('SIAP.components.field.ComboTahun', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.combotahun',
    fieldLabel: '',
    name: 'tahun',
    initComponent: function () {
        var me = this;
        var storemtahun = Ext.create('Ext.data.Store', {
            autoLoad: true,
            storeId: 'storemtahun',
            fields: ['tahun'],
            proxy: {
                type: 'ajax',
                url: Settings.MASTER_URL + '/c_bulantahun/gettahun',
                reader: {
                    type: 'json',
                    root: 'data',
                },
            },
        });
        Ext.apply(me, {
            store: storemtahun,
            triggerAction: 'all',
            editable: true,
            displayField: 'tahun',
            valueField: 'tahun',
            name: me.name,
        });
        me.callParent([arguments]);
    },
});
