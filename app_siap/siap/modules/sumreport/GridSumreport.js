Ext.define("SIAP.modules.sumreport.GridSumreport", {
  extend: "Ext.grid.Panel",
  alternateClassName: "SIAP.GridSumreport",
  alias: "widget.GridSumreport",
  initComponent: function () {
    var me = this;
    me.addEvents({ beforeload: true });
    var storesumreport = Ext.create("Ext.data.Store", {
      storeId: "storesumreport",
      autoLoad: true,
      pageSize: Settings.PAGESIZE,
      proxy: {
        type: "ajax",
        url: Settings.SITE_URL + "/dailyreport/getsumdailyreport",
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
        "pegawaiid",
        "satkerid",
        "tahun",
        "nik",
        "namadepan",
        "lokasiid",
        "lokasi",
        "divisi",
        "departemen",
        "level",
        "jabatan",
        "bulanno",
        "bulan",
        "weekday",
        "weekend",
        "weekdayna",
        "weekendna",
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
      store: storesumreport,
      columns: [
        { header: "No", xtype: "rownumberer", width: 30 },
        { header: "NIK", dataIndex: "nik", width: 80 },
        { header: "Nama", dataIndex: "namadepan", width: 150 },
        {
          header: "Unit",
          align: "left",
          columns: [
            { header: "Divisi", dataIndex: "divisi", width: 120 },
            { header: "Departemen", dataIndex: "departemen", width: 120 },
            { header: "Level", dataIndex: "level", width: 120 },
            { header: "Jabatan", dataIndex: "jabatan", width: 120 },
          ],
        },
        { header: "Lokasi", dataIndex: "lokasi", width: 120 },
        { header: "Bulan", dataIndex: "bulan", width: 120 },
        { header: "Tahun", dataIndex: "tahun", width: 120 },
        {
          header: "Approved",
          align: "left",
          columns: [
            { header: "Weekday", dataIndex: "weekday", width: 80 },
            { header: "Weekend", dataIndex: "weekend", width: 80 },
          ],
        },
        {
          header: "Not Approved",
          align: "left",
          columns: [
            { header: "Weekday", dataIndex: "weekdayna", width: 80 },
            { header: "Weekend", dataIndex: "weekendna", width: 80 },
          ],
        },
      ],
      bbar: Ext.create("Ext.toolbar.Paging", {
        displayInfo: true,
        height: 35,
        store: "storesumreport",
      }),
    });
    me.callParent([arguments]);
  },
});
