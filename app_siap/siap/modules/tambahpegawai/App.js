Ext.define("SIAP.modules.tambahpegawai.App", {
  extend: "Ext.form.Panel",
  alternateClassName: "SIAP.tambahpegawai",
  alias: "widget.tambahpegawai",
  requires: [
    "SIAP.components.field.ComboStatusPegawai",
    "SIAP.components.field.FieldJabatan",
    "SIAP.components.field.FieldSatker",
    "SIAP.components.field.ComboLevel",
    "SIAP.components.field.ComboLokasiKerja",
  ],
  initComponent: function () {
    var me = this;

    var tplFoto = new Ext.XTemplate(
      '<tpl for=".">',
      '<img src="{url}" width="150" height="171" />',
      "</tpl>"
    );

    Ext.apply(me, {
      layout: "border",
      items: [
        {
          xtype: "panel",
          region: "center",
          autoScroll: true,
          bodyPadding: 10,
          tbar: [
            "->",
            {
              text: "Simpan",
              glyph: "xf0c7@FontAwesome",
              handler: function () {
                me.tambah();
              },
            },
            {
              text: "Batal",
              glyph: "xf00d@FontAwesome",
              handler: function () {
                Ext.History.add("#pegawai");
              },
            },
          ],
          items: [
            {
              layout: "column",
              baseCls: "x-plain",
              border: false,
              items: [
                {
                  xtype: "panel",
                  columnWidth: 0.4,
                  bodyPadding: 10,
                  layout: "form",
                  defaultType: "displayfield",
                  baseCls: "x-plain",
                  border: false,
                  defaults: {
                    labelWidth: 170,
                  },
                  items: [
                    {
                      xtype: "textfield",
                      fieldLabel: "NIK",
                      name: "nik",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Nama",
                      name: "fullname",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Nama Depan",
                      name: "namadepan",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Nama Belakang",
                      name: "namabelakang",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Nama Keluarga",
                      name: "namakeluarga",
                      anchor: "95%",
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
                      xtype: "combostatuspegawai",
                      fieldLabel: "Status Pegawai",
                      name: "statuspegawai",
                      anchor: "95%",
                    },
                    { xtype: "hidden", name: "satkerid" },
                    { xtype: "hidden", name: "jabatanid" },
                    { xtype: "hidden", name: "levelid" },
                    {
                      xtype: "fieldsatker",
                      fieldLabel: "Direktorat",
                      name: "direktorat",
                      anchor: "95%",
                      listeners: {
                        pilih: function (p, record) {
                          me.getForm()
                            .findField("satkerid")
                            .setValue(record.get("id"));
                          me.getForm()
                            .findField("direktorat")
                            .setValue(record.get("direktorat"));
                          me.getForm()
                            .findField("divisi")
                            .setValue(record.get("divisi"));
                          me.getForm()
                            .findField("departemen")
                            .setValue(record.get("departemen"));
                          me.getForm()
                            .findField("seksi")
                            .setValue(record.get("seksi"));
                          me.getForm()
                            .findField("subseksi")
                            .setValue(record.get("subseksi"));
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
                          me.getForm()
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
                          me.getForm()
                            .findField("levelid")
                            .setValue(rec[0].data.id);
                        },
                      },
                    },
                    {
                      xtype: "combolokasikerja",
                      fieldLabel: "Lokasi Kerja",
                      name: "lokasiid",
                      anchor: "95%",
                      listeners: {
                        pilih: function (p, rec) {
                          me.getForm()
                            .findField("lokasiid")
                            .setValue(rec.get("id"));
                        },
                      },
                    },
                    {
                      xtype: "datefield",
                      fieldLabel: "Tgl Masuk",
                      name: "tglmasuk",
                      format: "d/m/Y",
                      anchor: "95%",
                    },
                  ],
                },
              ],
            },
          ],
        },
      ],
    });
    me.callParent([arguments]);
  },
  tambah: function () {
    var me = this;
    var formp = me.getForm();
    formp.submit({
      url: Settings.SITE_URL + "/pegawai/tambahPegawai",
      waitTitle: "Menyimpan...",
      waitMsg: "Sedang menyimpan data, mohon tunggu...",
      success: function (form, action) {
        var obj = Ext.decode(action.response.responseText);
        if (obj.success) {
          Ext.History.add("#pegawai");
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
});
