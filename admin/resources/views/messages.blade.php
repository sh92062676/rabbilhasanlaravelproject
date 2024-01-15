@extends('layout.app')
@section('title', 'Messages')
@section('content')
<div id="MessageDiv" class="container d-none">
    <div class="row">
    <div class="col-md-12 p-5">
    <table id="messageTable" class="table table-striped table-sm table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th class="th-sm">SL</th>
          <th class="th-sm">Name</th>
          <th class="th-sm">Mobile</th>
          <th class="th-sm">Email</th>
          <th class="th-sm">Message</th>
          <th class="th-sm">Delete</th>
        </tr>
      </thead>
      <tbody id="message_table">
        <tr>
          
        </tr>

      </tbody>
    </table>
    
    </div>
    </div>
</div>

<div id="LoaderDivMessage" class="container">
    <div class="row">
      <div class="col-md-12">
        <img width="200" class="d-block m-auto mt-5" src="{{asset('images/Animation.gif')}}" alt="">
      </div>
    </div>
  </div>

  <div id="WrongDivMessage" class="container d-none">
    <div class="row">
      <div class="col-md-12">
        <h3 class="text-center mt-5">Data Not Found. <br>Something Went Wrong!</h3>
      </div>
    </div>
  </div>
@endsection


@section('script')
<script>
 getMessageData()
 function getMessageData() {
    axios.get('/getmessages')
        .then(function (response) {
            if (response.status = 200) {
                $('#MessageDiv').removeClass('d-none');
                $('#LoaderDivMessage').addClass('d-none');
                var dataJSON = response.data;

                // Clear existing content in the table before appending new data
                if ($.fn.DataTable.isDataTable('#messageTable')) {
                        $('#messageTable').DataTable().clear().destroy();
                    }
                $('#message_table').empty();
                $.each(dataJSON, function (i, item,sl) {
                    var sl = i + 1;
                    $('<tr>').html(
                        "<td>" + sl++ + "</td>" +
                        "<td>" + dataJSON[i].name + "</td>" +
                        "<td> " + dataJSON[i].mobile + "</td>" +
                        "<td> " + dataJSON[i].email + "</td>" +
                        "<td> " + dataJSON[i].msg + "</td>" +
                        "<td> <a data-id=" + dataJSON[i].id + " class='deletemessage' ><i class='fas fa-trash-alt'></i></a> </td>"
                    ).appendTo('#message_table');
                });

                $(document).ready(function () {
                        $('#messageTable').DataTable({
                            "lengthMenu": [5,10,15, 25, 50, 100, 500, 1000,2000,5000],
                            "pageLength": 25,
                            order:false
                        });
                        $('.dataTables_length').addClass('bs-select');
                    });
            } else {
                $('#LoaderDivMessage').addClass('d-none');
                $('#WrongDivMessage').removeClass('d-none');
            }
        })
        .catch(function (error) {
            $('#LoaderDivMessage').addClass('d-none');
            $('#WrongDivMessage').removeClass('d-none');
        });
}
$(document).on('click', '.deletemessage', function () {
    var messageId = $(this).data('id');
    console.log(messageId)
    deleteMessage(messageId);
});

function deleteMessage(messageId){ 
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            showLoaderOnConfirm: true,
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    icon: "error",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Perform the deletion logic here
                // Example: making an API call to delete the service
                axios.get('/deletemessage/' + messageId)
                    .then((response) => {
                        if(response.status = 1){ 
                        // Show success message after successful deletion
                        Swal.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success",
                        });}
                        // Update the content on the page without reloading
                        $('#message_table').empty(); // Clear the table content

                        // Fetch updated services data and re-render the table
                        getMessageData();
                        // Additional logic if needed after successful deletion
                    })
                    .catch((error) => {
                        // Show error message if deletion fails
                        Swal.fire({
                            title: "Error!",
                            text: error.message || "Failed to delete the file.",
                            icon: "error"
                        });
                        // Additional error handling if needed
                    });
            }
        });
}

</script>
@endsection