@extends('layout.app')
@section('title', 'Blog')
@section('content')

<div id="BlogDiv" class="container d-none">
    <div class="row">
    <div class="col-md-12 p-5">
        <button class="addNewBlog btn btn-sm btn-danger">Add New</button>
    <table id="blogTable" class="table table-striped table-sm table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th class="th-sm">Image</th>
          <th class="th-sm">Caption</th>
          <th class="th-sm">Description</th>
          <th class="th-sm">Date</th>
          <th class="th-sm">Link</th>
          <th class="th-sm">Edit</th>
          <th class="th-sm">Delete</th>
        </tr>
      </thead>
      <tbody id="blog_table">
        <tr>
          
        </tr>

      </tbody>
    </table>
    
    </div>
    </div>
</div>

<div id="LoaderDivBlog" class="container">
    <div class="row">
      <div class="col-md-12">
        <img width="200" class="d-block m-auto mt-5" src="{{asset('images/Animation.gif')}}" alt="">
      </div>
    </div>
  </div>

  <div id="WrongDivBlog" class="container d-none">
    <div class="row">
      <div class="col-md-12">
        <h3 class="text-center mt-5">Data Not Found. <br>Something Went Wrong!</h3>
      </div>
    </div>
  </div>

@endsection
@section('script')
<script>
getBlogData()

function getBlogData() {
    axios.get('/getblogs')
        .then(function (response) {
            if (response.status = 200) {
                $('#BlogDiv').removeClass('d-none');
                $('#LoaderDivBlog').addClass('d-none');
                var dataJSON = response.data;

                // Clear existing content in the table before appending new data
                if ($.fn.DataTable.isDataTable('#blogTable')) {
                        $('#blogTable').DataTable().clear().destroy();
                    }
                $('#blog_table').empty();
                $.each(dataJSON, function (i, item,sl) {
                    var sl = i + 1;
                    $('<tr>').html(
                        "<td> <img height='50' src=" + dataJSON[i].img + " alt='blog image'></td>" +
                        "<td>" + dataJSON[i].caption + "</td>" +
                        "<td> " + dataJSON[i].des + "</td>" +
                        "<td> " + dataJSON[i].date + "</td>" +
                        "<td> " + dataJSON[i].link + "</td>" +
                        "<td> <a data-id=" + dataJSON[i].id + " class='editblog' ><i class='fas fa-edit'></i></a> </td>"+
                        "<td> <a data-id=" + dataJSON[i].id + " class='deleteblog' ><i class='fas fa-trash-alt'></i></a> </td>"
                    ).appendTo('#blog_table');
                });

                $(document).ready(function () {
                        $('#blogTable').DataTable({
                            "lengthMenu": [5,10,15, 25, 50, 100, 500, 1000,2000,5000],
                            "pageLength": 25,
                            order:false
                        });
                        $('.dataTables_length').addClass('bs-select');
                    });
            } else {
                $('#LoaderDivBlog').addClass('d-none');
                $('#WrongDivBlog').removeClass('d-none');
            }
        })
        .catch(function (error) {
            $('#LoaderDivBlog').addClass('d-none');
            $('#WrongDivBlog').removeClass('d-none');
        });
}

        // Add New Blog


        $(document).on('click', '.addNewBlog', function() {
            addNewBlog();
        });
    
        function addNewBlog(){
            Swal.fire({
                title: "Add New Blog",
                html: `
                    <input type="text" id="caption" placeholder="Enter Your Blog Caption" class="swal2-input" value=""><br>
                    <input type="text" id="des" placeholder="Enter Blog Description" class="swal2-input" value=""><br>
                    <input type="text" id="date" placeholder="Enter Blog Date" class="swal2-input" value=""><br>
                    <input type="text" id="link" placeholder="Enter Blog Link" class="swal2-input" value=""><br>
                    <input type="text" id="image" placeholder="Enter Blog Image" class="swal2-input" value=""><br>
                `,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Save"
            }).then((result) => {
                if (result.isConfirmed) {
                    const name = document.getElementById('caption').value;
                    const description = document.getElementById('des').value;
                    const date = document.getElementById('date').value;
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
                    if(name.length==0 || description.length==0 || link.length==0||image.length==0||date.length==0){
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
                        axios.post('/addblog', {
                            name: name,
                            description: description,
                            date: date,
                            image: image,
                            link: link,
                            // Add more fields as needed
                        })
                            .then((response) => {
                                // Handle success response if needed
                                Swal.fire({
                                    title: "Blog Added!",
                                    text: response.data.message, // Success message from Laravel
                                    icon: "success",
                                });
                                   // Update the content on the page without reloading
                                   $('#blog_table').empty(); // Clear the table content
        
                                   // Fetch updated services data and re-render the table
                                   getBlogData();
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


// Edit Blogs

$(document).on('click', '.editblog', function () {
    var blogId = $(this).data('id');
    editBlog(blogId);
});     

function editBlog(blogId) {
    axios.get('/editblog/' + blogId)
        .then((response) => {
            const blogData = response.data;

            Swal.fire({
                title: "Edit Service",
                html: `
                    <input type="text" id="caption" placeholder="Enter Caption" class="swal2-input" value="${blogData.caption}"><br>
                    <input type="text" id="description" placeholder="Enter description" class="swal2-input" value="${blogData.des}"><br>
                    <input type="text" id="date" placeholder="Enter date" class="swal2-input" value="${blogData.date}"><br>
                    <input type="text" id="link" placeholder="Enter link" class="swal2-input" value="${blogData.link}"><br>
                    <input type="text" id="img" placeholder="Enter image" class="swal2-input" value="${blogData.img}"><br>
                `,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Save"
            }).then((result) => {
                if (result.isConfirmed) {
                    const caption = document.getElementById('caption').value;
                    const description = document.getElementById('description').value;
                    const date = document.getElementById('date').value;
                    const link = document.getElementById('link').value;
                    const img = document.getElementById('img').value;

                    Swal.fire({
                        title: 'Updating...',
                        icon: "question",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    if(caption.length==0 || description.length==0||date.length==0||link.length==0||img.length==0){
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "You Can't Keep Empty Anu Field",
                            showConfirmButton: false,
                          });
                    }else{ 
                    // Send updated data to the backend for update
                        axios.post('/updateblog/' + blogId, {
                            caption: caption,
                            description: description,
                            date: date,
                            link: link,
                            img: img,
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
                                   $('#blog_table').empty(); // Clear the table content

                                   // Fetch updated services data and re-render the table
                                   getBlogData();
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

  // Delete Blogs

  $(document).on('click', '.deleteblog', function () {
    var blogId = $(this).data('id');
    deleteBlog(blogId);
});

function deleteBlog(blogId){ 
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
                axios.get('/deleteblog/' + blogId)
                    .then((response) => {
                        if(response.status = 1){ 
                        // Show success message after successful deletion
                        Swal.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success",
                        });}
                        // Update the content on the page without reloading
                        $('#blog_table').empty(); // Clear the table content

                        // Fetch updated services data and re-render the table
                        getBlogData();
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