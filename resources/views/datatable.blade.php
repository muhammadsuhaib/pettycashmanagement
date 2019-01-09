@extends('layout.master')

{{--@include('layout.searchjs')--}}
{{--@include('layout.searchcss')--}}

@section('body')

{{--<!DOCTYPE html>
<html>
<head>

   --}}{{-- <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/bootstrap-3.min.css">
    <link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="http://demo.itsolutionstuff.com/plugin/jquery.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>--}}{{--
</head>--}}

{{--<body>--}}

<div class="container">

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary" style="margin-top: 2%;padding:2%;">
                <div class="row">
                    <div class="col-md-12">


        <table id="users" class="table table-hover table-condensed" style="width:100%">
            <thead>
            <tr>
                <th>Id</th>
                <th>Branch Name</th>
                <th>Purchasing Date</th>
                <th>Type</th>
                <th>Subject</th>
                <th>Amount</th>
                <th>Section of Expend</th>
                <th>Note</th>
                {{--<th>Action</th>--}}


            </tr>
            </thead>
        </table>



                    </div></div></div></div></div></div>

  {{--  </body>
    </html>--}}
<script type="text/javascript">
   // $(document).ready(function() {
        oTable = $('#users').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('datatable.getposts') }}",
            "columns": [
                {data: 'id', name: 'id'},
                {data: 'branch_id', name: 'branch_id'},
                {data: 'purchasing_date', name: 'purchasing_date'},
                {data: 'type', name: 'type'},
                {data: 'subject', name: 'subject'},
                {data: 'amount', name: 'amount'},
                {data: 'department', name: 'department'},
                {data: 'note', name: 'note'},
//                {data: 'checker', name: 'checker'},









            ]
        });
  //  });
   console.log(oTable);
  //  alert(oTable);
</script>


@endsection