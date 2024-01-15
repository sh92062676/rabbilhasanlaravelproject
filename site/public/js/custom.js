// Owl Carousel Start..................



$(document).ready(function() {
    var one = $("#one");
    var two = $("#two");

    $('#customNextBtn').click(function() {
        one.trigger('next.owl.carousel');
    })
    $('#customPrevBtn').click(function() {
        one.trigger('prev.owl.carousel');
    })
    one.owlCarousel({
        autoplay:true,
        loop:true,
        dot:true,
        autoplayHoverPause:true,
        autoplaySpeed:100,
        margin:10,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            1000:{
                items:4
            }
        }
    });

    two.owlCarousel({
        autoplay:true,
        loop:true,
        dot:true,
        autoplayHoverPause:true,
        autoplaySpeed:100,
        margin:10,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }
    });

});



// Owl Carousel End..................



// Contact Submit


$(document).on('click', '#submit', function () {
    var name = $('#name').val();
    var mobile = $('#mobile').val();
    var email = $('#email').val();
    var msg = $('#msg').val();
    contactSubmit(name,mobile,email,msg);
});

function contactSubmit(name,mobile,email,msg){
    if(name==0){
        $('#submit').html('Enter Your Name');
        $('#name').on('input', function() {
            if ($(this).val().length === 0) {
                $('#submit').html('Enter Your Name');
            } else {
                $('#submit').html('পাঠিয়ে দিন');
            }
        });
    }
    else if(mobile==0){
        $('#submit').html('Enter Your Phone Number');
        $('#mobile').on('input', function() {
            if ($(this).val().length === 0) {
                $('#submit').html('Enter Your Phone Number');
            } else {
                $('#submit').html('পাঠিয়ে দিন');
            }
        });
    }
    else if(email==0){
        $('#submit').html('Enter Your Email');
        $('#email').on('input', function() {
            if ($(this).val().length === 0) {
                $('#submit').html('Enter Your Phone Number');
            } else {
                $('#submit').html('পাঠিয়ে দিন');
            }
        });
    }
    else if(msg==0){
        $('#submit').html('Enter Your Message');
        $('#msg').on('input', function() {
            if ($(this).val().length === 0) {
                $('#submit').html('Enter Your Phone Number');
            } else {
                $('#submit').html('পাঠিয়ে দিন');
            }
        });
    }
    else{
    $('#submit').html('Message Sending....');
    axios.post('/message', {
        name: name,
        mobile: mobile,
        email: email,
        msg: msg,
        // Add more fields as needed
    })
        .then((response) => {
            Swal.fire({
                title: "Message Sent!",
                text: response.data.message, // Success message from Laravel
                icon: "success",
            }).
            then(() => {
                // Clear input fields after the success message is closed
                document.getElementById('name').value = '';
                document.getElementById('email').value = '';
                document.getElementById('mobile').value = '';
                document.getElementById('msg').value = '';
                $('#submit').html('পাঠিয়ে দিন');
            });
        })
        .catch((error) => {

            Swal.fire({
                title: "Error!",
                text: error.message || "Failed to Send Message. Try Again",
                icon: "error"
            });
        });
    
        
    }
}