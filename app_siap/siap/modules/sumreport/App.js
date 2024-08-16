Ext.define("SIAP.modules.sumreport.App", {
  extend: "Ext.panel.Panel",
  alternateClassName: "SIAP.sumreport",
  alias: "widget.sumreport",
  requires: [
    "SIAP.components.tree.UnitKerja",
    "SIAP.modules.sumreport.GridSumreport",
    "SIAP.components.progressbar.WinProgress",
    "SIAP.components.field.ComboLevel",
    "SIAP.components.field.ComboLokasiKerja",
    "SIAP.components.field.FieldJabatan",
    "SIAP.components.field.ComboBulan",
    "SIAP.components.field.ComboTahun",
  ],
  initComponent: function () {
    var me = this;
    Ext.apply(me, {
      layout: "border",
      items: [
        {
          region: "west",
          title: "Daftar Unit Kerja",
          collapsible: true,
          collapsed: false,
          layout: "fit",
          border: false,
          resizable: { dynamic: true },
          items: [
            {
              xtype: "unitkerja",
              width: 200,
              border: false,
              listeners: {
                itemclick: function (a, b, c) {
                  me
                    .down("#id_gridsumreport")
                    .getStore().proxy.extraParams.satkerid = b.get("id");
                  me.down("#id_gridsumreport").getStore().loadPage(1);
                },
              },
              viewConfig: {
                plugins: {
                  ptype: "treeviewdragdrop",
                  dropGroup: "gridtotree",
                  enableDrag: false,
                },
                listeners: {
                  beforedrop: function (node, data, overModel, dropPos, opts) {
                    this.droppedRecords = data.records;
                    data.records = [];
                  },
                  drop: function (node, data, overModel, dropPos, opts) {
                    var dataFrom = this.droppedRecords;
                    var pegawaiid = [];
                    Ext.iterate(dataFrom, function (record) {
                      var temp = {
                        pegawaiid: record.get("pegawaiid"),
                        levelid: record.get("levelid"),
                        nik: record.get("nik"),
                        nama: record.get("nama"),
                        bulan: record.get("bulan"),
                        tahun: record.get("tahun"),
                        satkerid: record.get("satkerid"),
                        lokasikerjaid: record.get("lokasikerja"),
                      };
                      pegawaiid.push(temp);
                    });
                    this.droppedRecords = undefined;
                  },
                },
              },
            },
          ],
        },
        {
          itemId: "id_gridsumreport",
          xtype: "GridSumreport",
          region: "center",
          frame: true,
          listeners: {
            beforeload: function (store) {
              var satkerid = "";
              var m = Ext.ComponentQuery.query("#id_unitkerja")[0]
                .getSelectionModel()
                .getSelection();
              if (m.length > 0) {
                satkerid = m[0].get("id");
              }
              if (m.length > 0) {
                satkerid = m[0].get("id");
              }
            },
          },
          tbar: [
            {
              glyph: "xf002@FontAwesome",
              text: "Cari Data",
              handler: function () {
                me.winSearchPegawai();
              },
            },
            "->",
            {
              glyph: "xf02f@FontAwesome",
              text: "Cetak",
              handler: function () {
                var m = me.down("#id_gridsumreport").getStore()
                  .proxy.extraParams;
                // console.log(m);
                window.open(
                  Settings.SITE_URL +
                    "/dailyreport/cetakdokumensum?" +
                    objectParametize(m)
                );
              },
            },
          ],
        },
      ],
    });
    me.callParent([arguments]);
  },

  winSearchPegawai: function () {
    var me = this;

    var win = Ext.create("Ext.window.Window", {
      title: "Cari Pegawai",
      width: 400,
      closeAction: "destroy",
      modal: true,
      layout: "fit",
      autoScroll: false,
      autoShow: true,
      buttons: [
        {
          text: "Cari",
          handler: function () {
            var form = win.down("form").getForm();
            var nik = form.findField("nik").getValue();
            var nama = form.findField("nama").getValue();
            var levelid = form.findField("level").getValue();
            var lokasi = form.findField("lokasi").getValue();
            var bulan = form.findField("bulan").getValue();
            var tahun = form.findField("tahun").getValue();
            var tree = me
              .down("#id_unitkerja")
              .getSelectionModel()
              .getSelection();
            treeid = null;
            if (tree.length > 0) {
              treeid = tree[0].get("id");
            }
            me.down("#id_gridsumreport").getStore().proxy.extraParams.satkerid =
              treeid;
            me.down("#id_gridsumreport").getStore().proxy.extraParams.nik = nik;
            me.down("#id_gridsumreport").getStore().proxy.extraParams.nama =
              nama;
            me.down("#id_gridsumreport").getStore().proxy.extraParams.level =
              levelid;
            me.down("#id_gridsumreport").getStore().proxy.extraParams.bulan =
              bulan;
            me.down("#id_gridsumreport").getStore().proxy.extraParams.tahun =
              tahun;
            me
              .down("#id_gridsumreport")
              .getStore().proxy.extraParams.lokasikerja = lokasi;
            me.down("#id_gridsumreport").getStore().loadPage(1);
            win.destroy();
          },
        },
        {
          text: "Batal",
          handler: function () {
            win.destroy();
          },
        },
      ],
      items: [
        {
          xtype: "form",
          waitMsgTarget: true,
          bodyPadding: 15,
          layout: "anchor",
          defaultType: "textfield",
          region: "center",
          autoScroll: true,
          defaults: {
            labelWidth: 100,
            anchor: "100%",
          },
          items: [
            { fieldLabel: "NIK", name: "nik" },
            { fieldLabel: "Nama", name: "nama" },
            {
              xtype: "combobulan",
              fieldLabel: "Bulan",
              name: "bulan",
              emptyText: "Pilih Bulan",
            },
            {
              xtype: "combotahun",
              fieldLabel: "Tahun",
              name: "tahun",
              emptyText: "Pilih Tahun",
            },
            {
              xtype: "combolevel",
              fieldLabel: "Level",
              name: "level",
              listeners: {
                select: function (combo, rec, opt) {
                  me.getForm().findField("levelid").setValue(rec[0].data.id);
                },
              },
            },
            {
              xtype: "combolokasikerja",
              fieldLabel: "Lokasi Kerja",
              name: "lokasi",
              listeners: {
                pilih: function (p, rec) {
                  me.getForm().findField("lokasiid").setValue(rec.get("id"));
                },
              },
            },
          ],
        },
      ],
    });
  },
});
