@extends('layout')
@section('meta')@endsection
@section('contents')
    <h1>一斉送信</h1>{{$success??''}}
    <div id="messages-index" class="em-container">

    </div><!--end em-container-->
    <script>
        let params = {
            mst_maternities:{!! json_encode(App\Models\MstMaternity::get(),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT ) !!},
            tbl_users:{!! json_encode(App\Models\TblUser::get(),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT ) !!},

            success: '{{ session('success') ?? '' }}',
            error: '{{ session('error') ?? '' }}',
        };

    </script>

@endsection

@section('javascript')

@endsection
