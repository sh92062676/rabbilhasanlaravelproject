@extends('layout.app')
@section('title', 'Visitors')
@section('content')
    
<div class="container">
    <div class="row">
    <div class="col-md-12 p-5">
    <table id="VisitorDt" class="table table-striped table-sm table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th class="th-sm">NO</th>
          <th class="th-sm">IP</th>
          <th class="th-sm">Date & Time</th>
        </tr>
      </thead>
      <tbody>
        @php
            $sl=1;
        @endphp
        @foreach ($VisitorData as $visitor)
        <tr>
          <th class="th-sm">{{$sl++}}</th>
          <th class="th-sm">{{$visitor['ip_address']}}</th>
          <th class="th-sm">{{$visitor['visit_time']}}</th>
        </tr>
        @endforeach

      </tbody>
    </table>
    
    </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
  $(document).ready(function () {
$('#VisitorDt').DataTable({
    "lengthMenu": [5,10,15, 25, 50, 100, 500, 1000,2000,5000],
    order:false
});
$('.dataTables_length').addClass('bs-select');
});
</script>
    
@endsection