@extends('Layout.app')
@section('title','Conact')
@section('content')

    <div class="container-fluid jumbotron mt-5 ">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6  text-center">
                <h1 class="page-top-title mt-3">- যোগাযোগ করুন  -</h1>
            </div>
        </div>
    </div>

    <div id="contact" class="container">
        <div class="row">
            <div class="col-md-6">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14608.039158573767!2d90.36556225320301!3d23.74703030875986!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8b33cffc3fb%3A0x4a826f475fd312af!2sDhanmondi%2C%20Dhaka%201205!5e0!3m2!1sen!2sbd!4v1704105240033!5m2!1sen!2sbd" width="500" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-md-6">
                <h3 class="service-card-title">ঠিকানা</h3>
                <hr>
                <p class="footer-text"><i class="fas fa-map-marker-alt"></i> শেখেরটেক ৮ মোহাম্মদপুর, ঢাকা <i class="fas ml-2 fa-phone"></i>   ০১৭৮৫৩৮৮৯১৯  <i class="fas ml-2 fa-envelope"></i>   Rabbil@Yahoo.com</p>
                <h3 class="service-card-title">মেসেজ পাঠান </h3>
                <div class="form-group ">
                    <input id="name" type="text" class="form-control w-100" placeholder="আপনার নাম">
                </div>
                <div class="form-group">
                    <input id="mobile" type="tel" class="form-control  w-100" placeholder="মোবাইল নং ">
                </div>
                <div class="form-group">
                    <input id="email" type="text" class="form-control  w-100" placeholder="ইমেইল ">
                </div>
                <div class="form-group">
                    <input id="msg" type="text" class="form-control  w-100" placeholder="মেসেজ ">
                </div>
                <button id="submit" class="btn btn-block normal-btn w-100">পাঠিয়ে দিন </button>
            </div>
        </div>
    </div>
@endsection

