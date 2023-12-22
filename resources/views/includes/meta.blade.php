
<meta charset="utf-8">
@if(\PageHelper::get('robots') != 'yes')
    <meta name="robots" content="noindex,nofollow" />
@endif
<title>{{\PageHelper::get('title')}}</title>
<meta name="description" content="{{\PageHelper::get('description')}}">
@if(\PageHelper::get('keywords'))<meta name="keywords" content="{{\PageHelper::get('keywords')}}">@endif

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

<meta property="og:locale" content="ja_JP">
<meta property="og:site_name" content="{{config('const.SITE_NAME')}}">
<meta property="og:title" content="{{\PageHelper::get('title')}}">
<meta property="og:description" content="{{\PageHelper::get('description')}}">
<meta name="csrf-token" content="{{ csrf_token() }}">

@if(\PageHelper::get('tags'))
@foreach(\PageHelper::get('tags') AS $key=>$val)
<meta property="article:tag" content="{{$val}}">
@endforeach
@endif

@if(\PageHelper::get('canonical'))<link rel="canonical" href="{{\PageHelper::get('canonical')}}" />@endif

@if(\PageHelper::get('published_time'))<meta property="article:published_time" content="{{\PageHelper::get('published_time')}}">@endif
@if(\PageHelper::get('modified_time'))<meta property="article:modified_time" content="{{\PageHelper::get('modified_time')}}">@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{\PageHelper::get('title')}}">
<meta name="twitter:description" content="{{\PageHelper::get('description')}}">

@if(\PageHelper::get('prev'))
<link rel="prev" href="{{url(\PageHelper::get('prev'))}}">
@endif
@if(\PageHelper::get('next'))
<link rel="next" href="{{url(\PageHelper::get('next'))}}">
@endif

<link href="https://use.fontawesome.com/releases/v5.8.0/css/all.css" rel="stylesheet">

<link rel="shortcut icon" href="/favicon.ico" type="image/vnd.microsoft.icon">
<link rel="icon" href="/favicon.ico" type="image/vnd.microsoft.icon">

<script type="text/javascript">
    let global = {
        api_token:'{{auth()->user()->api_token??''}}',
        front_url:'{{config('birthstory.front_app_url')}}',
        payment_statuses:{1:'未回答',2:'回答済',3:'支払済',4:'却下',9:'その他'},
        is_use_instagrams:{1:'許可する',2:'許可しない'},
        front_app_basic_url:'{{config('birthstory.front_app_basic_url')}}',
    };
    @auth
        global.user = {!! ((\Auth::user()->toJson())) !!};
    @endauth
</script>


@vite(['resources/scss/app.scss','resources/js/app.js'])

{{--	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>--}}
<!-- inview JS -->
{{--	<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>--}}
{{--    <script src="/js/app.js?{{env('VERSION_NO')}}@if(env('APP_ENV')=='local'){{time()}}@endif" defer></script>--}}
{{--<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6000837450615052" crossorigin="anonymous"></script>--}}

@production
<!-- Google Tag Manager -->
{{--<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':--}}
{{--            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],--}}
{{--        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=--}}
{{--        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);--}}
{{--    })(window,document,'script','dataLayer','GTM-KJVMSRQ');</script>--}}
<!-- End Google Tag Manager -->

@endproduction

