@extends('layout.app')
@section('title', 'Reviews')
@section('content')
<div id="ReviewDiv" class="container d-none">
    <div class="row">
    <div class="col-lg-12 p-5">
      <button class="addNewReview btn btn-sm btn-danger">Add New</button>
    <table id="reviewTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th class="th-sm">SL</th>
          <th class="th-sm">Image</th>
          <th class="th-sm">Name</th>
          <th class="th-sm">Review</th>
          <th class="th-sm">Edit</th>
          <th class="th-sm">Delete</th>
        </tr>
      </thead>
      <tbody id="review_table">

        <tr>

        </tr>

      </tbody>
    </table>
    
    </div>
    </div>
</div>

  <div id="LoaderDivReview" class="container">
    <div class="row">
      <div class="col-md-12">
        <img width="200" class="d-block m-auto mt-5" src="{{asset('images/Animation.gif')}}" alt="">
      </div>
    </div>
  </div>

  <div id="WrongDivReview" class="container d-none">
    <div class="row">
      <div class="col-md-12">
        <h3 class="text-center mt-5">Data Not Found. <br>Something Went Wrong!</h3>
      </div>
    </div>
  </div>
@endsection

@section('script')
    <script>
        getReviewData()
        function getReviewData() {
axios.get('/getreviews')
.then(function (response) {
    if (response.status = 200) {
        $('#ReviewDiv').removeClass('d-none');
        $('#LoaderDivReview').addClass('d-none');
        var dataJSON = response.data;

        // Clear existing content in the table before appending new data
        if ($.fn.DataTable.isDataTable('#reviewTable')) {
                $('#reviewTable').DataTable().clear().destroy();
            }
        $('#review_table').empty();
        $.each(dataJSON, function (i, item,sl) {
            var sl = i + 1;
            $('<tr>').html(
                "<td>" + sl++ + "</td>" +
                "<td> <img height='50' src=" + dataJSON[i].img + " alt='blog image'></td>" +
                "<td>" + dataJSON[i].name + "</td>" +
                "<td> " + dataJSON[i].review + "</td>" +
                "<td> <a data-id=" + dataJSON[i].id + " class='editreview' ><i class='fas fa-edit'></i></a> </td>"+
                "<td> <a data-id=" + dataJSON[i].id + " class='deletereview' ><i class='fas fa-trash-alt'></i></a> </td>"
            ).appendTo('#review_table');
        });

        $(document).ready(function () {
                $('#reviewTable').DataTable({
                    "lengthMenu": [5,10,15, 25, 50, 100, 500, 1000,2000,5000],
                    "pageLength": 25,
                    order:false
                });
                $('.dataTables_length').addClass('bs-select');
            });
    } else {
        $('#LoaderDivReview').addClass('d-none');
        $('#WrongDivReview').removeClass('d-none');
    }
})
.catch(function (error) {
    $('#LoaderDivReview').addClass('d-none');
    $('#WrongDivReview').removeClass('d-none');
});
}


// Add New Blog


$(document).on('click', '.addNewReview', function() {
    addNewReview();
});

function addNewReview(){
    Swal.fire({
        title: "Add New Review",
        html: `
            <input type="text" id="img" placeholder="Enter Your Review Image" class="swal2-input" value=""><br>
            <input type="text" id="name" placeholder="Enter Reviewer Name" class="swal2-input" value=""><br>
            <textarea placeholder="Enter The Review" name="" id="Review" cols="10" style="width: 62%; margin: 0 auto; margin-top: 20px;" rows="3" class="form-control"></textarea>
        `,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Save"
    }).then((result) => {
        if (result.isConfirmed) {
            const img = document.getElementById('img').value;
            const name = document.getElementById('name').value;
            const review = document.getElementById('Review').value;

            Swal.fire({
                title: 'Adding...',
                icon: "question",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            if(name.length==0 || img.length==0 || review.length==0){
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
                axios.post('/addreview', {
                    name: name,
                    img: img,
                    review: review,
                    // Add more fields as needed
                })
                    .then((response) => {
                        // Handle success response if needed
                        Swal.fire({
                            title: "Review Added!",
                            text: response.data.message, // Success message from Laravel
                            icon: "success",
                        });
                            // Update the content on the page without reloading
                            $('#review_table').empty(); // Clear the table content

                            // Fetch updated services data and re-render the table
                            getReviewData();
                    })
                    .catch((error) => {
                        // Handle error if the update fails
                        Swal.fire({
                            title: "Error!",
                            text: error.message || "Failed to delete Review.",
                            icon: "error"
                        });
                    });
            }
        }
    });
}

// Edit Review

$(document).on('click', '.editreview', function () {
    var reviewId = $(this).data('id');
    editReview(reviewId);
});     

function editReview(reviewId) {
    axios.get('/editreview/' + reviewId)
        .then((response) => {
            const reviewData = response.data;

            Swal.fire({
                title: "Edit Review",
                html: `
                    <input type="text" id="img" placeholder="Enter Image Link" class="swal2-input" value="${reviewData.img}"><br>
                    <input type="text" id="name" placeholder="Enter Reviewer Name" class="swal2-input" value="${reviewData.name}"><br>
                    <textarea placeholder="Enter The Review" name="" id="review" cols="10" style="width: 62%; margin: 0 auto; margin-top: 20px;" rows="3" class="form-control">${reviewData.review}</textarea>
                `,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Save"
            }).then((result) => {
                if (result.isConfirmed) {
                    const name = document.getElementById('name').value;
                    const img = document.getElementById('img').value;
                    const review = document.getElementById('review').value;

                    Swal.fire({
                        title: 'Updating...',
                        icon: "question",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    if(name.length==0 || img.length==0||review.length==0){
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "You Can't Keep Empty Any Field",
                            showConfirmButton: false,
                          });
                    }else{ 
                    // Send updated data to the backend for update
                        axios.post('/updatereview/' + reviewId, {
                            name: name,
                            img: img,
                            review: review,
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
                                   $('#review_table').empty(); // Clear the table content

                                   // Fetch updated services data and re-render the table
                                   getReviewData();
                            })
                            .catch((error) => {
                                // Handle error if the update fails
                                Swal.fire({
                                    title: "Error!",
                                    text: error.message || "Failed to Update The Review.",
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

  // Delete Reviews

  $(document).on('click', '.deletereview', function () {
    var reviewId = $(this).data('id');
    deleteReview(reviewId);
});

function deleteReview(reviewId){ 
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
                axios.get('/deletereview/' + reviewId)
                    .then((response) => {
                        if(response.status = 1){ 
                        // Show success message after successful deletion
                        Swal.fire({
                            title: "Deleted!",
                            text: "Review has been deleted.",
                            icon: "success",
                        });}
                        // Update the content on the page without reloading
                        $('#review_table').empty(); // Clear the table content

                        // Fetch updated services data and re-render the table
                        getReviewData();
                        // Additional logic if needed after successful deletion
                    })
                    .catch((error) => {
                        // Show error message if deletion fails
                        Swal.fire({
                            title: "Error!",
                            text: error.message || "Failed to delete Review.",
                            icon: "error"
                        });
                        // Additional error handling if needed
                    });
            }
        });
}
    </script>
@endsection