Ext.define('SIAP.modules.countreport.App', {
    extend: 'Ext.panel.Panel',
    alternateClassName: 'SIAP.countreport',
    alias: 'widget.countreport',
    requires: ['SIAP.components.tree.UnitKerja', 'SIAP.modules.countreport.ReportCount', 'SIAP.components.progressbar.WinProgress', 'SIAP.components.field.ComboBulan', 'SIAP.components.field.ComboTahun'],
    initComponent: function () {
        var me = this;
        Ext.apply(me, {
            layout: 'border',
            items: [
                {
                    region: 'west',
                    title: 'Daftar Unit Kerja',
                    collapsible: true,
                    collapsed: false,
                    layout: 'fit',
                    border: false,
                    resizable: { dynamic: true },
                    items: [
                        {
                            xtype: 'unitkerja',
                            width: 200,
                            border: false,
                            listeners: {
                                itemclick: function (a, b, c) {
                                    me.down('#id_reportcount').getStore().proxy.extraParams.satkerid = b.get('id');
                                    me.down('#id_reportcount').getStore().loadPage(1);
                                },
                            },
                        },
                    ],
                },
                {
                    itemId: 'id_reportcount',
                    xtype: 'ReportCount',
                    region: 'center',
                    frame: true,
                    listeners: {
                        beforeload: function (store) {
                            var satkerid = '';
                            var m = Ext.ComponentQuery.query('#id_unitkerja')[0].getSelectionModel().getSelection();
                            var lokasiid = me.down('#id_unitkerja').getStore().proxy.extraParams.lokasiid;
                            var bulan = me.down('#id_bulan').getValue();
                            if (m.length > 0) {
                                satkerid = m[0].get('id');
                            }
                            var tahun = me.down('#id_tahun').getValue();
                            if (m.length > 0) {
                                satkerid = m[0].get('id');
                            }

                            store.proxy.extraParams.satkerid = satkerid;
                            store.proxy.extraParams.bulan = bulan;
                            store.proxy.extraParams.tahun = tahun;
                            store.proxy.extraParams.lokasiid = lokasiid;
                        },
                    },
                    tbar: [
                        'Periode :',
                        { itemId: 'id_bulan', xtype: 'combobulan', emptyText: 'Pilih Bulan' },
                        { itemId: 'id_tahun', xtype: 'combotahun', emptyText: 'Pilih Tahun' },
                        '-',
                        {
                            glyph: 'xf002@FontAwesome',
                            handler: function () {
                                me.down('#id_reportcount').getStore().load();
                            },
                        },
                        '->',
                        {
                            glyph: 'xf02f@FontAwesome',
                            text: 'Cetak',
                            handler: function () {
                                var m = me.down('#id_reportcount').getStore().proxy.extraParams;
                                window.open(Settings.SITE_URL + '/dailyreport/cetakdokumencount?' + objectParametize(m));
                            },
                        },
                    ],
                },
            ],
        });
        me.callParent([arguments]);
    },
});
