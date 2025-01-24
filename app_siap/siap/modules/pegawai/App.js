Ext.define("SIAP.modules.pegawai.App", {
  extend: "Ext.panel.Panel",
  alternateClassName: "SIAP.pegawai",
  alias: "widget.pegawai",
  requires: [
    "SIAP.components.tree.UnitKerja",
    "SIAP.modules.pegawai.GridPegawai",
    "SIAP.components.field.ComboStatusPegawai",
    "SIAP.components.field.FieldJabatan",
    "SIAP.components.field.FieldSatker",
    "SIAP.components.field.ComboLevel",
    "SIAP.components.field.ComboLokasiKerja",
  	"SIAP.components.field.ComboApproval",
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
                    .down("#id_gridpegawai")
                    .getStore().proxy.extraParams.satkerid = b.get("id");
                  me.down("#id_gridpegawai").getStore().loadPage(1);
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
                        statuspegawaiid: record.get("statuspegawaiid"),
                        tglmulai: record.get("tglmulai"),
                        jabatanid: record.get("jabatanid"),
                        levelid: record.get("levelid"),
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
          itemId: "id_gridpegawai",
          xtype: "gridpegawai",
          region: "center",
          frame: true,
          tbar: [
            {
              glyph: "xf002@FontAwesome",
              text: "Cari Pegawai",
              handler: function () {
                me.winSearchPegawai();
              },
            },
            "->",
            // {
            //   glyph: "xf196@FontAwesome",
            //   text: "Tambah",
            //   handler: function () {
            //     Ext.History.add("#tambahpegawai");
            //   },
            // },
            {
              text: "Ubah",
              glyph: "xf044@FontAwesome",
              handler: function () {
                var m = me.down("grid").getSelectionModel().getSelection();
                if (m.length > 0) {
                  me.wincrud("2", m[0]);
                } else {
                  Ext.Msg.alert("Pesan", "Harap pilih data terlebih dahulu");
                }
              },
            },
          ],
        },
      ],
      listeners: {
        afterrender: function () {
          // Ext.get('id_submenu').dom.style.display = 'none';
        },
      },
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
              : Ext.Date.format(form.findField("tglmulai").getValue(), "d/m/Y");
            var tglselesai = Ext.isEmpty(
              form.findField("tglselesai").getValue()
            )
              ? null
              : Ext.Date.format(
                  form.findField("tglselesai").getValue(),
                  "d/m/Y"
                );
            var statuspegawai = form.findField("statuspegawai").getValue();
            var jeniskelamin = form.findField("jeniskelamin").getValue();
            var lokasi = form.findField("lokasi").getValue();
            var tree = me
              .down("#id_unitkerja")
              .getSelectionModel()
              .getSelection();
            treeid = null;
            if (tree.length > 0) {
              treeid = tree[0].get("id");
            }

            me.down("#id_gridpegawai").getStore().proxy.extraParams.satkerid =
              treeid;
            me.down("#id_gridpegawai").getStore().proxy.extraParams.nik = nik;
            me.down("#id_gridpegawai").getStore().proxy.extraParams.nama = nama;
            me.down("#id_gridpegawai").getStore().proxy.extraParams.tglmulai =
              tglmulai;
            me.down("#id_gridpegawai").getStore().proxy.extraParams.tglselesai =
              tglselesai;
            me
              .down("#id_gridpegawai")
              .getStore().proxy.extraParams.statuspegawai = statuspegawai;
            me
              .down("#id_gridpegawai")
              .getStore().proxy.extraParams.jeniskelamin = jeniskelamin;
            me.down("#id_gridpegawai").getStore().proxy.extraParams.lokasiid =
              lokasi;
            me.down("#id_gridpegawai").getStore().loadPage(1);
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
              fieldLabel: "Tgl Mulai",
              format: "d/m/Y",
              name: "tglmulai",
            },
            {
              xtype: "datefield",
              fieldLabel: "Tgl Selesai",
              format: "d/m/Y",
              name: "tglselesai",
            },
            {
              xtype: "combostatuspegawai",
              fieldLabel: "Status Pegawai",
              name: "statuspegawai",
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
          ],
        },
      ],
    });
  },

  wincrud: function (flag, records) {
    var me = this;
    var win = Ext.create("Ext.window.Window", {
      title: "Edit Data Pegawai",
      width: 500,
      closeAction: "destroy",
      modal: true,
      layout: "fit",
      autoScroll: true,
      autoShow: true,
      buttons: [
        {
          text: "Simpan",
          handler: function () {
            var formp = win.down("form").getForm();
            formp.submit({
              url: Settings.SITE_URL + "/pegawai/crudPegawai",
              waitTitle: "Menyimpan...",
              waitMsg: "Sedang menyimpan data, mohon tunggu...",
              success: function (form, action) {
                var obj = Ext.decode(action.response.responseText);
                if (obj.success) {
                  win.destroy();
                  me.down("grid").getSelectionModel().deselectAll();
                  me.down("grid").getStore().reload();
                  window.location.reload(true);
                }
              },
              failure: function (form, action) {
                switch (action.failureType) {
                  case Ext.form.action.Action.CLIENT_INVALID:
                    Ext.Msg.alert("Failure", "Harap isi semua data");
                    break;
                  case Ext.form.action.Action.CONNECT_FAILURE:
                    Ext.Msg.alert("Failure", "Terjadi kesalahan");
                    break;
                  case Ext.form.action.Action.SERVER_INVALID:
                    Ext.Msg.alert("Failure", action.result.msg);
                }
              },
            });
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
          bodyPadding: 20,
          layout: "anchor",
          defaultType: "textfield",
          region: "center",
          autoScroll: true,
          defaults: {
            labelWidth: 100,
            anchor: "100%",
          },
          items: [
            { xtype: "hidden", name: "flag", value: flag },
            { xtype: "hidden", name: "pegawaiid", value: me.params },
            { xtype: "hidden", name: "satkerid" },
	    { xtype: "hidden", name: "satkerdisp" },
            { xtype: "hidden", name: "jabatanid" },
            { xtype: "hidden", name: "levelid" },
            { xtype: "hidden", name: "statuspegawaiid" },
            { xtype: "hidden", name: "lokasikerja" },
            { xtype: "hidden", name: "atasanid" },

            { fieldLabel: "Nama", name: "nama", anchor: "95%", readOnly: true },

            /*
            { xtype: "fieldsatker",
              fieldLabel: "Direktorat",
              name: "direktorat",
              listeners: {
                pilih: function (p, rec) {
                  win
                    .down("form")
                    .getForm()
                    .findField("satkerid")
                    .setValue(rec.get("id"));
                  win
                    .down("form")
                    .getForm()
                    .findField("direktorat")
                    .setValue(rec.get("direktorat"));
                  win
                    .down("form")
                    .getForm()
                    .findField("divisi")
                    .setValue(rec.get("divisi"));
                  win
                    .down("form")
                    .getForm()
                    .findField("departemen")
                    .setValue(rec.get("departemen"));
                  win
                    .down("form")
                    .getForm()
                    .findField("seksi")
                    .setValue(rec.get("seksi"));
                  win
                    .down("form")
                    .getForm()
                    .findField("subseksi")
                    .setValue(rec.get("subseksi"));
                },
              },
            },
            {
              xtype: "textfield",
              fieldLabel: "Divisi",
              name: "divisi",
              anchor: "95%",
              readOnly: true,
            },
            {
              xtype: "textfield",
              fieldLabel: "Departemen",
              name: "departemen",
              anchor: "95%",
              readOnly: true,
            },
            {
              xtype: "textfield",
              fieldLabel: "Seksi",
              name: "seksi",
              anchor: "95%",
              readOnly: true,
            },
            {
              xtype: "textfield",
              fieldLabel: "Sub Seksi",
              name: "subseksi",
              anchor: "95%",
              readOnly: true,
            },
            {
              xtype: "fieldjabatan",
              fieldLabel: "Jabatan",
              name: "jabatan",
              anchor: "95%",
              listeners: {
                pilih: function (p, rec) {
                  win
                    .down("form")
                    .getForm()
                    .findField("jabatanid")
                    .setValue(rec.get("id"));
                },
              },
            },
            {
              xtype: "combolevel",
              fieldLabel: "Level",
              name: "level",
              anchor: "95%",
              listeners: {
                select: function (combo, rec, opt) {
                  win
                    .down("form")
                    .getForm()
                    .findField("levelid")
                    .setValue(rec[0].data.id);
                },
              },
            },
            {
              xtype: "combobox",
              fieldLabel: "Jenis Kelamin",
              name: "jeniskelamin",
              anchor: "95%",
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
              xtype: "datefield",
              fieldLabel: "Tgl Masuk",
              format: "d/m/Y",
              name: "tglmulai",
              anchor: "95%",
              readOnly: true,
            },
            {
              xtype: "datefield",
              fieldLabel: "Tgl Keluar",
              format: "d/m/Y",
              name: "tglselesai",
              anchor: "95%",
            },
            {
              xtype: "combostatuspegawai",
              fieldLabel: "Status Pegawai",
              name: "statuspegawai",
              anchor: "95%",
              listeners: {
                select: function (combo, rec, opt) {
                  win
                    .down("form")
                    .getForm()
                    .findField("statuspegawaiid")
                    .setValue(rec[0].data.id);
                },
              },
            },
            */

            {
              xtype: "combolokasikerja",
              fieldLabel: "Lokasi Kerja",
              name: "lokasi",
              anchor: "95%",
              listeners: {
                select: function (combo, rec, opt) {
                  win
                    .down("form")
                    .getForm()
                    .findField("lokasikerja")
                    .setValue(rec[0].data.id);
                },
              },
            },
            {
              xtype: "comboapproval",
              fieldLabel: "Nama Approval",
              name: "atasannik",
              anchor: "95%",
              listeners: {
                select: function (combo, rec, opt) {
                  win
                    .down("form")
                    .getForm()
                    .findField("atasanid")
                    .setValue(rec[0].data.id);
                },
              },
            },
            {
              xtype: "combobox",
              fieldLabel: "Backdate Access",
              name: "accessod",
              anchor: "45%",
              queryMode: "local",
              displayField: "text",
              valueField: "id",
              store: Ext.create("Ext.data.Store", {
                fields: ["id", "text"],
                data: [
                  { id: 1, text: "Ya" },
                  { id: 0, text: "Tidak" },
                ],
              }),
            },
            {
              xtype: "numberfield",
              fieldLabel: "Lama Open (Hari)",
              name: "jmlhari",
              anchor: "35%",
            },
          ],
        },
      ],
    });
    if (flag == "2") {
      win.down("form").getForm().loadRecord(records);
    }
  },
});
