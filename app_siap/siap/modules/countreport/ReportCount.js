Ext.define('SIAP.modules.countreport.ReportCount', {
    extend: 'Ext.grid.Panel',
    alternateClassName: 'SIAP.ReportCount',
    alias: 'widget.ReportCount',
    requires: ['SIAP.components.field.ComboBulan', 'SIAP.components.field.ComboTahun'],
    initComponent: function () {
        var me = this;
        me.addEvents({ beforeload: true });
        var storecountreport = Ext.create('Ext.data.Store', {
            storeId: 'storecountreport',
            autoLoad: true,
            pageSize: Settings.PAGESIZE,
            proxy: {
                type: 'ajax',
                url: Settings.SITE_URL + '/dailyreport/getcountdailyreport',
                actionMethods: {
                    create: 'POST',
                    read: 'POST',
                },
                reader: {
                    type: 'json',
                    root: 'data',
                    totalProperty: 'count',
                },
            },
            fields: ['pegawaiid', 'satkerid', 'tahun', 'nik', 'namadepan', 'lokasi', 'divisi', 'departemen', 'level', 'jabatan', 'bulantahun', 'bulan', 'weekday', 'weekend'],
            listeners: {
                beforeload: function (store) {
                    me.fireEvent('beforeload', store);
                },
            },
        });
        Ext.apply(me, {
            layout: 'fit',
            autoScroll: true,
            frame: false,
            border: true,
            loadMask: true,
            stripeRows: true,
            store: storecountreport,
            columns: [
                { header: 'No', xtype: 'rownumberer', width: 30 },
                { header: 'NIK', dataIndex: 'nik', width: 80 },
                { header: 'Nama', dataIndex: 'namadepan', width: 150 },
                {
                    header: 'Unit',
                    align: 'left',
                    columns: [
                        { header: 'Divisi', dataIndex: 'divisi', width: 120 },
                        { header: 'Departemen', dataIndex: 'departemen', width: 120 },
                        { header: 'Level', dataIndex: 'level', width: 120 },
                        { header: 'Jabatan', dataIndex: 'jabatan', width: 120 },
                    ],
                },
                { header: 'Bulan', dataIndex: 'bulan', width: 120 },
                {
                    header: 'Jumlah Report',
                    align: 'center',
                    columns: [
                        { header: 'Weekdays', dataIndex: 'weekday', width: 120 },
                        { header: 'Weekend', dataIndex: 'weekend', width: 120 },
                    ],
                },
            ],
            bbar: Ext.create('Ext.toolbar.Paging', {
                displayInfo: true,
                height: 35,
                store: 'storecountreport',
            }),
        });
        me.callParent([arguments]);
    },
});
