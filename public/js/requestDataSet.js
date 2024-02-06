
$(function () {
  $("#example1").DataTable({
    "responsive": true, "lengthChange": true, "autoWidth": false,
  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

});

  //failed-order details download as a text::

$(".pdf-download-btn").on('click', function() {
  const data = $(this).data("json");
  // console.log(data);

  // // Convert JSON to string
  const jsonString = JSON.stringify(data, null, 2);
  console.log(jsonString);

  // // Create a Blob containing the JSON data
  const blob = new Blob([jsonString], { type: 'text/plain' });

  // // Create a download link
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = 'data.txt';

  // // Append the link to the body
  document.body.appendChild(link);

  // // Trigger a click on the link to start the download
  link.click();

});


                          






