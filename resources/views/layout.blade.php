<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
@include('includes.meta')
@yield('meta')
</head>
<body page-name="{{\PageHelper::getConf()['name']}}">
@include('includes.gtm')

<nav id="navi" class="fixed bg-white w-45 text-sm shadow-md h-screen">
    <p class="m-auto border-b border-slate-200 w-40 mb-3.75 py-2.5"><img class="inline-block" src="/images/logo.svg" width="200" /></p>

    <div class="mb-[10px]"><a class="block py-2.5 pl-2.5 hover:bg-slate-100 font-bold" href="/"><i class="text-main mr-[5px] fa-solid fa-caret-right"></i>ダッシュボード</a></div>

    <section class="menu-list">

        <section class="box">
            <h3><span>管理</span></h3>
            <ul>
                <li><a href="/patients"><i class="fa-solid fa-caret-right"></i>提出データ</a></li>
                <li><a href="/messages"><i class="fa-solid fa-caret-right"></i>一斉送信</a></li>
            </ul>
        </section>

        <section class="box">
            <h3><span>システム設定</span></h3>
            <ul>
                <li><a href="/logout"><i class="fa-solid fa-caret-right"></i>ログアウト</a></li>
            </ul>
        </section>
    </section>
</nav>

<main id="main">
    @yield('contents')
</main>


<div id="modal"></div>
<div id="loading"></div>


@yield('javascript')
@env(['local','staging'])<span id="test-flg-88">テスト</span>@endenv
