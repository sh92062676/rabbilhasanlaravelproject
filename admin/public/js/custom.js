


$(document).on('click', '.addNewPhoto', function() {
    addNewPhoto();
});

function addNewPhoto(){
    Swal.fire({
        title: "Add New Photo",
        html: `
            <input type="file" id="inputPhoto" placeholder="Choose a photo" class="mb-3" value=""><br>
            <img width="400" id="imgPreview" src="">
        `,
        
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Save",
        didOpen: () => {
            // Attach change event to #inputPhoto here
            $('#inputPhoto').on('change', function() {
                const file = this.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    $('#imgPreview').attr('src', e.target.result);
                };

                reader.readAsDataURL(file);
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let PhotoFile = $('#inputPhoto').prop('files')[0];
            var formData = new FormData();

            formData.append('photo', PhotoFile);
            
            Swal.fire({
                title: 'Uploading...',
                icon: "question",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            // Send updated data to the backend for update
                axios.post('/photoUpload', formData)
                    .then((response) => {
                        //Handle success response if needed
                        Swal.fire({
                            title: "Photo Added!",
                            text: response.data.message, // Success message from Laravel
                            icon: "success",
                        });
                        $('.Gallery').empty();
                        loadPhoto();
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
    });
}


function loadPhoto(){
    axios.get('/photojson').then(function(response){
        $.each(response.data, function (i, item) {
            $("<div class='col-md-3'>").html(
                "<img data-id="+item['id']+" class='imgOnRow p-1' src="+item['location']+" alt=''>"+
                "<button data-id="+item['id']+" data-photo="+item['location']+" class='btn deletephoto btn-sm'>delete</button>"
            ).appendTo('.Gallery');
        });
        $('.deletephoto').on('click', function(event){
            let id= $(this).data('id')
            let photo= $(this).data('photo')

            photoDelete(photo,id);

            event.preventDefault()
        });

    }).catch(function(error){

    })
}

$('#loadMore').on('click', function(){
    let LoadMoreBtn = $(this);
    let firstImage = $(this).closest('div').find('img').data('id');
    LoadById(firstImage, LoadMoreBtn)
})
var imgId =0;
function LoadById(id, LoadMoreBtn){
    imgId= imgId+12;
    let photoId = imgId+id;
    var URL = "/PhotoJsonById/"+photoId;
    LoadMoreBtn.html("<div class='spinner-border spinner-border-sm' role='status'></div>")
    axios.get(URL).then(function(response){
        LoadMoreBtn.html("Load More")
        $.each(response.data, function (i, item) {
            $("<div class='col-md-3'>").html(
                "<img data-id="+item['id']+" class='imgOnRow p-1' src="+item['location']+" alt=''>"+
                "<button data-id="+item['id']+" data-photo="+item['location']+" class='btn deletephoto btn-sm'>delete</button>"
            ).appendTo('.Gallery');
        });

        $('.deletephoto').on('click', function(event){
            let photo= $(this).data('photo')
            let id= $(this).data('id')

            
            photoDelete(photo,id);
            event.preventDefault()
        });
    }).catch(function(error){

    })
}

function photoDelete(oldPhotoUrl, id) {
    let formData = new FormData();
    formData.append('OldPhotoURL', oldPhotoUrl);
    formData.append('OldPhotoId', id);

    axios.post('/photoDelete', formData)
    .then(function(response){
        if(response.data.success){
            // Success message handling (for example using SweetAlert2)
            Swal.fire({
                title: "Photo Deleted!",
                text: "The photo has been successfully deleted.",
                icon: "success",
            });
            // Reload the photos or update the UI as needed
            $('.Gallery').empty();
            loadPhoto();
        } else {
            // Error message handling
            Swal.fire({
                title: "Error!",
                text: response.error,
                icon: "error"
            });
        }
    })
    .catch(function(error){
        // Catch any Axios request error
        console.error("Error:", error);
        // Display error message if request fails
        Swal.fire({
            title: "Error!",
            text: "Failed to delete the photo.",
            icon: "error"
        });
    });
}


