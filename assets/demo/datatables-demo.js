// Call the dataTables jQuery plugin
$(document).ready(function () {
  $('#dataTable').DataTable({
    dom: 'Bfrtip',
    buttons: [
      { extend: 'csv', className: 'btn btn-primary btn-sm' },
      { extend: 'excel', className: 'btn btn-primary btn-sm' },
      { extend: 'pdf', className: 'btn btn-primary btn-sm' },
      { extend: 'print', className: 'btn btn-primary btn-sm' }
    ],
  });
  $('#dataTable_invoice').DataTable({
    "order": [[0, "desc"]]
  });
});




setTimeout(function () {
  $('.dt-button').each(function () {
    $(this).removeClass('dt-button');
  })
}, 100);