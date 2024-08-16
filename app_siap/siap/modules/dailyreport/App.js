Ext.define("SIAP.modules.dailyreport.App", {
  extend: "Ext.panel.Panel",
  alternateClassName: "SIAP.dailyreport",
  alias: "widget.dailyreport",
  requires: [
    "SIAP.components.tree.UnitKerja",
    "SIAP.modules.dailyreport.GridDailyreport",
    "SIAP.components.field.ComboStatusDailyreport",
    "SIAP.components.progressbar.WinProgress",
    "SIAP.components.field.ComboLevel",
    "SIAP.components.field.ComboLokasiKerja",
    "SIAP.components.field.ComboStatusDailyreport",
    "SIAP.components.field.FieldJabatan",
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
                    .down("#id_griddailyreport")
                    .getStore().proxy.extraParams.satkerid = b.get("id");
                  me.down("#id_griddailyreport").getStore().loadPage(1);
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
                        tglmulai: record.get("tglmulai"),
                        tglselesai: record.get("tglselesai"),
                        levelid: record.get("levelid"),
                        jeniskelamin: record.get("jeniskelamin"),
                        satkerid: record.get("satkerid"),
                        lokasikerjaid: record.get("lokasikerja"),
                        statusid: record.get("statusid"),
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
          itemId: "id_griddailyreport",
          xtype: "GridDailyreport",
          region: "center",
          frame: true,
          listeners: {
            beforeload: function (store) {
              var satkerid = "";
              var lokasiid = me.down("#id_unitkerja").getStore().proxy
                .extraParams.lokasiid;
              var tglstr = moment().subtract(7, "days").format("YYYY-MM-DD");
              var tglend = moment().format("YYYY-MM-DD");

              store.proxy.extraParams.lokasiid = lokasiid;
              store.proxy.extraParams.tglstr = tglstr;
              store.proxy.extraParams.tglend = tglend;
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
                var m = me.down("#id_griddailyreport").getStore()
                  .proxy.extraParams;
                console.log(m);
                window.open(
                  Settings.SITE_URL +
                    "/dailyreport/cetakdokumen?" +
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
      autoScroll: true,
      autoShow: true,
      buttons: [
        {
          text: "Cari",
          handler: function () {
            var form = win.down("form").getForm();
            var nik = form.findField("nik").getValue();
            var nama = form.findField("nama").getValue();
            var tglmulai = Ext.isEmpty(form.findField("tglmulai").getValue())
              ? null
              : Ext.Date.format(form.findField("tglmulai").getValue(), "Y-m-d");
            var tglselesai = Ext.isEmpty(
              form.findField("tglselesai").getValue()
            )
              ? null
              : Ext.Date.format(
                  form.findField("tglselesai").getValue(),
                  "Y-m-d"
                );
            var levelid = form.findField("level").getValue();
            var jeniskelamin = form.findField("jeniskelamin").getValue();
            var lokasi = form.findField("lokasi").getValue();
            var statusid = form.findField("statusid").getValue();
            var tree = me
              .down("#id_unitkerja")
              .getSelectionModel()
              .getSelection();
            treeid = null;
            if (tree.length > 0) {
              treeid = tree[0].get("id");
            }
            me
              .down("#id_griddailyreport")
              .getStore().proxy.extraParams.satkerid = treeid;
            me.down("#id_griddailyreport").getStore().proxy.extraParams.nik =
              nik;
            me.down("#id_griddailyreport").getStore().proxy.extraParams.nama =
              nama;
            me
              .down("#id_griddailyreport")
              .getStore().proxy.extraParams.tglmulai = tglmulai;
            me
              .down("#id_griddailyreport")
              .getStore().proxy.extraParams.tglselesai = tglselesai;
            me.down("#id_griddailyreport").getStore().proxy.extraParams.level =
              levelid;
            me
              .down("#id_griddailyreport")
              .getStore().proxy.extraParams.jeniskelamin = jeniskelamin;
            me
              .down("#id_griddailyreport")
              .getStore().proxy.extraParams.statusid = statusid;
            me
              .down("#id_griddailyreport")
              .getStore().proxy.extraParams.lokasikerja = lokasi;
            me.down("#id_griddailyreport").getStore().loadPage(1);
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
              xtype: "datefield",
              fieldLabel: "Tgl Mulai Report",
              format: "d/m/Y",
              name: "tglmulai",
              value: moment().subtract(7, "days").format("DD/MM/YYYY"),
            },
            {
              xtype: "datefield",
              fieldLabel: "Tgl Akhir Report",
              format: "d/m/Y",
              name: "tglselesai",
              value: moment().format("DD/MM/YYYY"),
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
              xtype: "combobox",
              fieldLabel: "Jenis Kelamin",
              name: "jeniskelamin",
              queryMode: "local",
              displayField: "text",
              valueField: "id",
              store: Ext.create("Ext.data.Store", {
                fields: ["id", "text"],
                data: [
                  { id: "L", text: "Pria" },
                  { id: "P", text: "Wanita" },
                ],
              }),
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
            {
              xtype: "combostatusdailyreport",
              fieldLabel: "Status Report",
              name: "statusid",
              listeners: {
                pilih: function (p, rec) {
                  me.getForm().findField("statusid").setValue(rec.get("id"));
                },
              },
            },
          ],
        },
      ],
    });
  },
});
