@extends('layout')
@section('meta')@endsection
@section('contents')
    <h1>提出データ</h1>
    <div id="patients-index" class="em-container">

    </div><!--end em-container-->
    <script>
        let params = {
            list:{!! json_encode($list->items(),JSON_PRETTY_PRINT) !!},
            search_params:{!! json_encode($search_params,JSON_UNESCAPED_UNICODE )!!},
            pagination:{!! json_encode(\Arr::except($list->toArray(), ['data'])??[],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT ) !!},
            mst_maternities:{!! json_encode(App\Models\MstMaternity::get(),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT ) !!},
            users:{!! json_encode(App\Models\TblUser::get(),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT ) !!},
        };

    </script>

@endsection

@section('javascript')

@endsection
