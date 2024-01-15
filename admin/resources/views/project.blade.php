@extends('layout.app')
@section('title', 'Projects')
@section('content')

<div id="projectDiv" class="container d-none">
    <div class="row">
    <div class="col-md-12 p-5">
      <button class="addNewProject btn btn-sm btn-danger">Add New</button>
    <table id="projectTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
            
            <th class="th-sm">Image</th>
          <th class="th-sm">Name</th>
          <th class="th-sm">Description</th>
          <th class="th-sm">Link</th>
          <th class="th-sm">Edit</th>
          <th class="th-sm">Delete</th>
        </tr>
      </thead>
      <tbody id="project_table">
      
        <tr>
          {{-- <th class="th-sm">Course Name</th>
          <th class="th-sm">1000 TK</th>
          <th class="th-sm">110</th>
          <th class="th-sm">200</th>
          <th class="th-sm"><a href="" ><i class="fas fa-eye"></i></a></th>
          <th class="th-sm"><a href="" ><i class="fas fa-edit"></i></a></th>
          <th class="th-sm"><a href="" ><i class="fas fa-trash-alt"></i></a></th> --}}
        </tr>	
        
      </tbody>
    </table>
    
    </div>
    </div>
</div>


    <div id="LoaderDivProject" class="container">
        <div class="row">
          <div class="col-md-12">
            <img width="200" class="d-block m-auto mt-5" src="{{asset('images/Animation.gif')}}" alt="">
          </div>
        </div>
    </div>
    
      <div id="WrongDivProject" class="container d-none">
        <div class="row">
          <div class="col-md-12">
            <h3 class="text-center mt-5">Data Not Found. <br>Something Went Wrong!</h3>
          </div>
        </div>
      </div>
@endsection
<label for="">Choose a photo</label>
@section('script')
<script>
  
  // get Project

getProjectData()

function getProjectData() {
    axios.get('/getprojects')
        .then(function (response) {
            if (response.status = 200) {
                console.log('Response Data:', response.data);
                $('#projectDiv').removeClass('d-none');
                $('#LoaderDivProject').addClass('d-none');
                var projectdata = response.data;
                
                if ($.fn.DataTable.isDataTable('#projectTable')) {
                    $('#projectTable').DataTable().clear().destroy();
                }
                $('#project_table').empty();

                $.each(projectdata, function (i, item) {
                    $('<tr>').html(
                        "<td> <img height='50' src=" + projectdata[i].project_img + " alt=''></td>" +
                        "<td>" + projectdata[i].project_name + "</td>" +
                        "<td> " + projectdata[i].project_des + "</td>" +
                        "<td> " + projectdata[i].project_link + "</td>" +
                        "<td> <a data-id=" + projectdata[i].id + " class='editProject' ><i class='fas fa-edit'></i></a> </td>" +
                        "<td> <a data-id=" + projectdata[i].id + " class='deleteProject' ><i class='fas fa-trash-alt'></i></a> </td>"
                    ).appendTo('#project_table');
                });
                $('#projectTable').DataTable({
                    "lengthMenu": [5,10,15, 25, 50, 100, 500, 1000,2000,5000],
                });
                $('.dataTables_length').addClass('bs-select');
                
            } else {
                $('#LoaderDivProject').addClass('d-none');
                $('#WrongDivProject').removeClass('d-none');
            }
        })
        .catch(function (error) {
            $('#LoaderDivProject').addClass('d-none');
            $('#WrongDivProject').removeClass('d-none');
        });
}

        // Add New Project


        $(document).on('click', '.addNewProject', function() {
            addNewProject();
        });
    
        function addNewProject(){
            Swal.fire({
                title: "Add New Project",
                html: `
                    <input type="text" id="name" placeholder="Enter Project name" class="swal2-input" value=""><br>
                    <input type="text" id="des" placeholder="Enter Project Description" class="swal2-input" value=""><br>
                    <input type="text" id="link" placeholder="Enter Project Link" class="swal2-input" value=""><br>
                    <input type="text" id="image" placeholder="Enter Project Image" class="swal2-input" value=""><br>
                `,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Save"
            }).then((result) => {
                if (result.isConfirmed) {
                    const name = document.getElementById('name').value;
                    const description = document.getElementById('des').value;
                    const link = document.getElementById('link').value;
                    const image = document.getElementById('image').value;
        
                    Swal.fire({
                        title: 'Adding...',
                        icon: "question",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    if(name.length==0 || description.length==0 || link.length==0||image.length==0){
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "You Can't Keep Empty Any Field",
                            didOpen: () => {
                                Swal.hideLoading();
                            }
                          });
                    }else{ 
                    // Send updated data to the backend for update
                        axios.post('/addproject', {
                            name: name,
                            description: description,
                            image: image,
                            link: link,
                            // Add more fields as needed
                        })
                            .then((response) => {
                                // Handle success response if needed
                                Swal.fire({
                                    title: "Project Added!",
                                    text: response.data.message, // Success message from Laravel
                                    icon: "success",
                                });
                                   // Update the content on the page without reloading
                                   $('#project_table').empty(); // Clear the table content
        
                                   // Fetch updated services data and re-render the table
                                   getProjectData();
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

  // Delete Project

  $(document).on('click', '.deleteProject', function () {
    var projectId = $(this).data('id');
    deleteCourse(projectId);
});

function deleteCourse(projectId){ 
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
                axios.get('/deleteproject/' + projectId)
                    .then((response) => {
                        if(response.status = 1){ 
                        // Show success message after successful deletion
                        Swal.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success",
                        });}
                        // Update the content on the page without reloading
                        $('#project_table').empty(); // Clear the table content

                        // Fetch updated services data and re-render the table
                        getProjectData();
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

// Edit Project

$(document).on('click', '.editProject', function () {
    var projectId = $(this).data('id');
    editProject(projectId);
});

function editProject(projectId) {
    axios.get('/editproject/' + projectId)
        .then((response) => {
            const projectData = response.data;

            Swal.fire({
                title: "Edit Project",
                html: `
                    <input type="text" id="name" placeholder="Enter Project name" class="swal2-input" value="${projectData.project_name}"><br>
                    <input type="text" id="description" placeholder="Enter description" class="swal2-input" value="${projectData.project_des}"><br>
                    <input type="text" id="link" placeholder="Enter description" class="swal2-input" value="${projectData.project_link}"><br>
                    <input type="text" id="image" placeholder="Enter description" class="swal2-input" value="${projectData.project_img}"><br>
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
                    const link = document.getElementById('link').value;
                    const image = document.getElementById('image').value;

                    Swal.fire({
                        title: 'Updating...',
                        icon: "question",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    if(name.length==0 || description.length==0||link.length==0||image.length==0){
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "You Can't Keep Empty Any Field",
                            showConfirmButton: false,
                          });
                    }else{ 
                    // Send updated data to the backend for update
                        axios.post('/updateproject/' + projectId, {
                            name: name,
                            description: description,
                            link: link,
                            image: image,
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
                                   $('#project_table').empty(); // Clear the table content

                                   // Fetch updated services data and re-render the table
                                   getProjectData();
                            })
                            .catch((error) => {
                                // Handle error if the update fails
                                Swal.fire({
                                    title: "Error!",
                                    text: error.message || "Failed to Update the file.",
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
</script>
    
@endsection