@extends('layout.app')
@section('title', 'Services')
@section('content')

<div id="DataDiv" class="container d-none">
    <div class="row">
    <div class="col-lg-12 p-5">
      <button class="addNew btn btn-sm btn-danger">Add New</button>
    <table id="serviceTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th class="th-sm">Image</th>
          <th class="th-sm">Name</th>
          <th class="th-sm">Description</th>
          <th class="th-sm">Edit</th>
          <th class="th-sm">Delete</th>
        </tr>
      </thead>
      <tbody id="service_table">


              
      </tbody>
    </table>
    
    </div>
    </div>
</div>

  <div id="LoaderDiv" class="container">
    <div class="row">
      <div class="col-md-12">
        <img width="200" class="d-block m-auto mt-5" src="{{asset('images/Animation.gif')}}" alt="">
      </div>
    </div>
  </div>

  <div id="WrongDiv" class="container d-none">
    <div class="row">
      <div class="col-md-12">
        <h3 class="text-center mt-5">Data Not Found. <br>Something Went Wrong!</h3>
      </div>
    </div>
  </div>

@endsection

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

@section('script')
<script>
  function getServiceData() {
    axios.get('/getservices')
        .then(function (response) {
            if (response.status = 200) {
                $('#DataDiv').removeClass('d-none');
                $('#LoaderDiv').addClass('d-none');
                var dataJSON = response.data;

                // Clear existing content in the table before appending new data
                if ($.fn.DataTable.isDataTable('#serviceTable')) {
                        $('#serviceTable').DataTable().clear().destroy();
                    }
                $('#service_table').empty();

                $.each(dataJSON, function (i, item) {
                    $('<tr>').html(
                        "<td> <img class='table-img' src=" + dataJSON[i].service_img + "> </td>" +
                        "<td>" + dataJSON[i].service_name + "</td>" +
                        "<td> " + dataJSON[i].service_des + "</td>" +
                        "<td> <a data-id=" + dataJSON[i].id + " class='editService' ><i class='fas fa-edit'></i></a> </td>" +
                        "<td> <a data-id=" + dataJSON[i].id + " class='deleteService' ><i class='fas fa-trash-alt'></i></a> </td>"
                    ).appendTo('#service_table');
                });

                $(document).ready(function () {
                        $('#serviceTable').DataTable({
                            "lengthMenu": [5,10,15, 25, 50, 100, 500, 1000,2000,5000],
                        });
                        $('.dataTables_length').addClass('bs-select');
                    });
            } else {
                $('#LoaderDiv').addClass('d-none');
                $('#WrongDiv').removeClass('d-none');
            }
        })
        .catch(function (error) {
            $('#LoaderDiv').addClass('d-none');
            $('#WrongDiv').removeClass('d-none');
        });
}
  getServiceData()



// Add the event listeners outside the getServiceData function
$(document).on('click', '.deleteService', function () {
    var serviceId = $(this).data('id');
    deleteService(serviceId);
});

$(document).on('click', '.editService', function () {
    var serviceId = $(this).data('id');
    editService(serviceId);
});



function deleteService(serviceId){
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
                axios.get('/deleteservice/' + serviceId)
                    .then((response) => {
                        if(response.status = 1){ 
                        // Show success message after successful deletion
                        Swal.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success",
                        });}
                        // Update the content on the page without reloading
                        $('#service_table').empty(); // Clear the table content

                        // Fetch updated services data and re-render the table
                        getServiceData();
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

// Edit Service

// Remove the event listener from the function definition
function editService(serviceId) {
    axios.get('/editservice/' + serviceId)
        .then((response) => {
            const serviceData = response.data;

            Swal.fire({
                title: "Edit Service",
                html: `
                    <input type="text" id="name" placeholder="Enter name" class="swal2-input" value="${serviceData.service_name}"><br>
                    <input type="text" id="description" placeholder="Enter description" class="swal2-input" value="${serviceData.service_des}"><br>
                    <img height="100" class="mt-3" src="${serviceData.service_img}" alt=""><br>
                    <input type="file" id="image" accept="image/*" class="mt-3">
                `,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Save"
            }).then((result) => {
                if (result.isConfirmed) {
                    const name = document.getElementById('name').value;
                    const description = document.getElementById('description').value;

                    Swal.fire({
                        title: 'Updating...',
                        icon: "question",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    if(name.length==0 || description.length==0){
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "You Can't Keep Empty Name And Description Field",
                            showConfirmButton: false,
                          });
                    }else{ 
                    // Send updated data to the backend for update
                        axios.post('/updateservice/' + serviceId, {
                            name: name,
                            description: description,
                            // Add more fields as needed
                        })
                            .then((response) => {
                                // Handle success response if needed
                                Swal.fire({
                                    title: "Updated!",
                                    text: response.data.message, // Success message from Laravel
                                    icon: "success",
                                });
                                   // Update the content on the page without reloading
                                   $('#service_table').empty(); // Clear the table content

                                   // Fetch updated services data and re-render the table
                                   getServiceData();
                            })
                            .catch((error) => {
                                // Handle error if the update fails
                                Swal.fire({
                                    title: "Error!",
                                    text: error.message || "Failed to delete the file.",
                                    icon: "error"
                                });
                            });
                    }
                }
            });
        })
        .catch((error) => {
            // Handle error fetching service details
            console.error(error);
        });
}

// Remove previous click event listeners before attaching a new one
$(document).off('click', '.editService').on('click', '.editService', function() {
    var serviceId = $(this).data('id');
    editService(serviceId);
});




// add new service

$(document).on('click', '.addNew', function() {
    addNew();
});

function addNew(){
    Swal.fire({
        title: "Add New Service",
        html: `
            <input type="text" id="name" placeholder="Enter name" class="swal2-input" value=""><br>
            <input type="text" id="description" placeholder="Enter description" class="swal2-input" value=""><br>
            <input type="file" id="image" accept="image/*" class="mt-3">
        `,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Save"
    }).then((result) => {
        if (result.isConfirmed) {
            const name = document.getElementById('name').value;
            const description = document.getElementById('description').value;
            const image = document.getElementById('image').value;

            Swal.fire({
                title: 'Adding...',
                icon: "question",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            if(name.length==0 || description.length==0){
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "You Can't Keep Empty Name And Description Field",
                    showConfirmButton: false,
                  });
            }else{ 
            // Send updated data to the backend for update
                axios.post('/addservice', {
                    name: name,
                    description: description,
                    image: image.name
                    // Add more fields as needed
                })
                    .then((response) => {
                        // Handle success response if needed
                        Swal.fire({
                            title: "Added!",
                            text: response.data.message, // Success message from Laravel
                            icon: "success",
                        });
                           // Update the content on the page without reloading
                           $('#service_table').empty(); // Clear the table content

                           // Fetch updated services data and re-render the table
                           getServiceData();
                    })
                    .catch((error) => {
                        // Handle error if the update fails
                        Swal.fire({
                            title: "Error!",
                            text: error.message || "Failed to delete the file.",
                            icon: "error"
                        });
                    });
            }
        }
    });
}

</script>
    
@endsection