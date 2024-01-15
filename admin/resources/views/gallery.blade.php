@extends('layout.app')
@section('title', 'Photo Gellary')
@section('content')
<div class="col-md-12 p-5">
    <button class="addNewPhoto btn btn-sm btn-danger">Add New</button>
</div>


<div class="container-fluid">
    <div class="row Gallery">
    </div>
    <button class="btn btn-primary" id="loadMore">Load More</button>
</div>
@endsection

@section('script')
<script type="text/javascript">
loadPhoto()

</script>
    
@endsection