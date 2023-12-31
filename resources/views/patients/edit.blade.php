@extends('layout')
@section('meta')@endsection
@section('contents')
    <h1>提出データ</h1>
    <div id="patients-edit" class="em-container-detail">

    </div><!--end em-container-->
    <script>
        let params = {
            tbl_patient:{!! json_encode($tbl_patient,JSON_PRETTY_PRINT) !!},
        };

    </script>

@endsection

@section('javascript')

@endsection
