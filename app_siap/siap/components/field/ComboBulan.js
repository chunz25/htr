Ext.define('SIAP.components.field.ComboBulan', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.combobulan',
    fieldLabel: '',
    name: 'bulan',
    initComponent: function () {
        var me = this;
        var storembulan = Ext.create('Ext.data.Store', {
            autoLoad: true,
            storeId: 'storembulan',
            fields: ['bulan'],
            proxy: {
                type: 'ajax',
                url: Settings.MASTER_URL + '/c_bulantahun/getbulan',
                reader: {
                    type: 'json',
                    root: 'data',
                },
            },
        });
        Ext.apply(me, {
            store: storembulan,
            triggerAction: 'all',
            editable: true,
            displayField: 'bulan',
            valueField: 'bulan',
            name: me.name,
        });
        me.callParent([arguments]);
    },
});
