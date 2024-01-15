@extends('layout.app')
@section('title', 'Courses')
@section('content')

<div id="mainDiv" class="container d-none">
    <div class="row">
    <div class="col-md-12 p-5">
      <button class="addNewCourse btn btn-sm btn-danger">Add New</button>
    <table id="courseTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th class="th-sm">Name</th>
          <th class="th-sm">Fee</th>
          <th class="th-sm">Class</th>
          <th class="th-sm">Enrolled</th>
          <th class="th-sm">Details</th>
          <th class="th-sm">Edit</th>
          <th class="th-sm">Delete</th>
        </tr>
      </thead>
      <tbody id="course_table">
      
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


    <div id="LoaderDivCourse" class="container">
        <div class="row">
          <div class="col-md-12">
            <img width="200" class="d-block m-auto mt-5" src="{{asset('images/Animation.gif')}}" alt="">
          </div>
        </div>
    </div>
    
      <div id="WrongDivCourse" class="container d-none">
        <div class="row">
          <div class="col-md-12">
            <h3 class="text-center mt-5">Data Not Found. <br>Something Went Wrong!</h3>
          </div>
        </div>
      </div>
@endsection

@section('script')
<script>

      function getCourseData() {
        axios.get('/getcourses')
            .then(function (response) {
                if (response.status = 200) {
                    $('#mainDiv').removeClass('d-none');
                    $('#LoaderDivCourse').addClass('d-none');
                    var jsonData = response.data;
    
                    // Clear existing content in the table before appending new data
                    if ($.fn.DataTable.isDataTable('#courseTable')) {
                        $('#courseTable').DataTable().clear().destroy();
                    }
                    $('#course_table').empty();
    
                    $.each(jsonData, function (i, item) {
                        $('<tr>').html(
                            "<td>"+ jsonData[i].course_name + "</td>" +
                            "<td>" + jsonData[i].course_fee + "</td>" +
                            "<td> " + jsonData[i].course_totalclass + "</td>" +
                            "<td> " + jsonData[i].course_totalenroll + "</td>" +
                            "<td> <a data-id=" + jsonData[i].id + " class='courseDetails' ><i class='fas fa-eye'></i></a></td>" +
                            "<td> <a data-id=" + jsonData[i].id + " class='editCource' ><i class='fas fa-edit'></i></a> </td>" +
                            "<td> <a data-id=" + jsonData[i].id + " class='deleteCourse' ><i class='fas fa-trash-alt'></i></a> </td>"
                        ).appendTo('#course_table');
                    });

                      
                        $('#courseTable').DataTable({
                            "lengthMenu": [5,10,15, 25, 50, 100, 500, 1000,2000,5000],
                            "stateSave": true
                        });
                        $('.dataTables_length').addClass('bs-select');
                       
                } else {
                    $('#LoaderDivCourse').addClass('d-none');
                    $('#WrongDivCourse').removeClass('d-none');
                }
            })
            .catch(function (error) {
                $('#LoaderDivCourse').addClass('d-none');
                $('#WrongDivCourse').removeClass('d-none');
            });
    }
    getCourseData()
        // Add New Courses


    $(document).on('click', '.addNewCourse', function() {
        addNewCourse();
    });

    function addNewCourse(){
        Swal.fire({
            title: "Add New Course",
            html: `
                <input type="text" id="name" placeholder="Enter Course name" class="swal2-input" value=""><br>
                <input type="text" id="des" placeholder="Enter Description Fee" class="swal2-input" value=""><br>
                <input type="text" id="fee" placeholder="Enter Course Fee" class="swal2-input" value=""><br>
                <input type="text" id="totalenroll" placeholder="Enter Total Enroll" class="swal2-input" value=""><br>
                <input type="text" id="totalclasses" placeholder="Enter Total Classes" class="swal2-input" value=""><br>
                <input type="text" id="courselink" placeholder="Enter Class Link" class="swal2-input" value=""><br>
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
                const description = document.getElementById('des').value;
                const fee = document.getElementById('fee').value;
                const totalenroll = document.getElementById('totalenroll').value;
                const totalclasses = document.getElementById('totalclasses').value;
                const courselink = document.getElementById('courselink').value;
                const image = document.getElementById('image').value;
    
                Swal.fire({
                    title: 'Adding...',
                    icon: "question",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                if(name.length==0 || description.length==0 || fee.length==0||totalenroll.length==0||totalclasses.length==0||courselink.length==0){
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
                    axios.post('/addcourse', {
                        name: name,
                        description: description,
                        image: image.name,
                        fee: fee,
                        totalenroll: totalenroll,
                        totalclasses: totalclasses,
                        courselink: courselink
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
                               $('#course_table').empty(); // Clear the table content
    
                               // Fetch updated services data and re-render the table
                               getCourseData();
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
  // Delete Course

$(document).on('click', '.deleteCourse', function () {
    var courseId = $(this).data('id');
    deleteCourse(courseId);
});

function deleteCourse(courseId){ 
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
                axios.get('/deletecourse/' + courseId)
                    .then((response) => {
                        if(response.status = 1){ 
                        // Show success message after successful deletion
                        Swal.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success",
                        });}
                        // Update the content on the page without reloading
                        $('#course_table').empty(); // Clear the table content

                        // Fetch updated services data and re-render the table
                        getCourseData();
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
// Edit Course

$(document).on('click', '.editCource', function () {
    var courseId = $(this).data('id');
    editCourse(courseId);
});

function editCourse(courseId) {
    axios.get('/editcourse/' + courseId)
        .then((response) => {
            const courseData = response.data;

            Swal.fire({
                title: "Edit Course",
                html: `
                    <input type="text" id="name" placeholder="Enter name" class="swal2-input" value="${courseData.course_name}"><br>
                    <input type="text" id="description" placeholder="Enter description" class="swal2-input" value="${courseData.course_des}"><br>
                    <input type="text" id="fee" placeholder="Enter description" class="swal2-input" value="${courseData.course_fee}"><br>
                    <input type="text" id="totalenroll" placeholder="Enter description" class="swal2-input" value="${courseData.course_totalenroll}"><br>
                    <input type="text" id="totalclass" placeholder="Enter description" class="swal2-input" value="${courseData.course_totalclass}"><br>
                    <input type="text" id="link" placeholder="Enter description" class="swal2-input" value="${courseData.course_link}"><br>
                    <img height="100" class="mt-3" src="${courseData.course_img}" alt=""><br>
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
                    const fee = document.getElementById('fee').value;
                    const totalenroll = document.getElementById('totalenroll').value;
                    const totalclass = document.getElementById('totalclass').value;
                    const link = document.getElementById('link').value;

                    Swal.fire({
                        title: 'Updating...',
                        icon: "question",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    if(name.length==0 || description.length==0||fee.length==0||totalenroll.length==0||totalclass.length==0||link.length==0){
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "You Can't Keep Empty Any Field",
                            showConfirmButton: false,
                          });
                    }else{ 
                    // Send updated data to the backend for update
                        axios.post('/updatecourse/' + courseId, {
                            name: name,
                            description: description,
                            fee: fee,
                            totalenroll: totalenroll,
                            totalclass: totalclass,
                            link: link
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
                                   $('#course_table').empty(); // Clear the table content

                                   // Fetch updated services data and re-render the table
                                   getCourseData();
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