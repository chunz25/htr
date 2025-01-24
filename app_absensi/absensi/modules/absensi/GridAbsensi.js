Ext.define("ABSENSI.modules.absensi.GridAbsensi", {
  extend: "Ext.grid.Panel",
  alternateClassName: "ABSENSI.GridAbsensi",
  alias: "widget.GridAbsensi",
  initComponent: function () {
    var me = this;
    me.addEvents({ beforeload: true });
    var storeabsensi = Ext.create("Ext.data.Store", {
      storeId: "storeabsensi",
      autoLoad: true,
      pageSize: Settings.PAGESIZE,
      proxy: {
        type: "ajax",
        url: Settings.SITE_URL + "/absensi/getListAbsensi",
        actionMethods: {
          create: "POST",
          read: "POST",
        },
        reader: {
          type: "json",
          root: "data",
          totalProperty: "count",
        },
      },
      fields: [
        "nik",
        "nama",
        "hari",
        "tgl",
        "scanmasuk",
        "scankeluar",
        "pengecualian",
        "ket",
      ],
      listeners: {
        beforeload: function (store) {
          me.fireEvent("beforeload", store);
        },
      },
    });
    Ext.apply(me, {
      layout: "fit",
      autoScroll: true,
      frame: false,
      border: true,
      loadMask: true,
      stripeRows: true,
      store: storeabsensi,
      columns: [
        { header: "No", xtype: "rownumberer", width: 30 },
        { header: "NIK", dataIndex: "nik", width: 80 },
        { header: "Nama", dataIndex: "nama", width: 150 },
        { header: "Hari", dataIndex: "hari", width: 120 },
        { header: "Tanggal", dataIndex: "tgl", width: 120 },
        { header: "Scan Masuk", dataIndex: "scanmasuk", width: 120 },
        { header: "Scan Keluar", dataIndex: "scankeluar", width: 120 },
        { header: "Pengecualian", dataIndex: "pengecualian", width: 120 },
        { header: "Keterangan", dataIndex: "ket", width: 120 },
      ],
      bbar: Ext.create("Ext.toolbar.Paging", {
        displayInfo: true,
        height: 35,
        store: "storeabsensi",
      }),
    });
    me.callParent([arguments]);
  },
});
