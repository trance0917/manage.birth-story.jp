<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('includes.meta')
    @yield('meta')
</head>
<body class="bg-gray-50">
@include('includes.gtm')

<main class="flex h-screen items-center justify-center">
    <div class="flex items-center pb-10">

        <form method="POST" action="{{ route('login') }}" class="w-100 mr-26">
            @csrf
            <h1 class="text-center text-2xl font-bold text-white bg-main-dark py-2.5">Admin Login</h1>
            <div class="bg-white px-10 shadow-md py-7.5">
                <dl>
                    <div class="mb-5 flex">
                        <dt><label class="block flex items-center justify-center bg-black text-center w-11.5 h-11.5"><i class="text-white fa-solid fa-user"></i></label></dt>
                        <dd class="flex-grow">
                            <input class="w-full border-0 border-b border-gray-300 bg-gray-100 placeholder:text-slate-400 h-11.5 focus:border-gray-500 focus:bg-sky-50 focus:shadow-inner focus:ring-0" type="email" name="email" value="{{old('email')}}" required autofocus autocomplete="username" placeholder="ID">
                            @if ($errors->has('email'))
                                <ul class="mt-1 text-sm text-red-600">
                                    @foreach ((array) $errors->get('email') as $message)
                                        <li>※{{ $message }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </dd>
                    </div>
                    <div class="flex mb-7.5">
                        <dt><label class="block flex items-center justify-center bg-black text-center w-11.5 h-11.5"><i class="text-white fa-solid fa-key"></i></label></dt>
                        <dd class="flex-grow">
                            <input class="w-full border-0 border-b border-gray-300 bg-gray-100 placeholder:text-slate-400 h-11.5 focus:border-gray-500 focus:bg-sky-50 focus:shadow-inner focus:ring-0" type="password" name="password" required autocomplete="current-password" placeholder="password">
                            @if ($errors->has('password'))
                                <ul class="mt-1 text-sm text-red-600">
                                    @foreach ((array) $errors->get('password') as $message)
                                        <li>※{{ $message }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </dd>
                    </div>
                </dl>

                <div class="block text-center mb-1.25">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-400 shadow-sm focus:ring-0 focus:ring-offset-0" name="remember">
                        <span class="ml-2 text-sm text-gray-700">ログインを保持する</span>
                    </label>
                </div>
                <button type="submit" class="w-full rounded-sm text-base font-bold text-white h-12.5 bg-orange hover:bg-opacity-80">ログイン</button>
            </div>
        </form>
        <div class="font-serif font-bold">
            <div><img src="/images/logo.png" alt=""></div>

            <p class="flex justify-center text-xl text-main mt-2.5 mb-3.75">「挑戦」を通じて、顧客を笑顔に</p>
            <div class="flex justify-center">
                <dl class="text-sm text-gray-600 space-y-0.5">
                    <div class="flex">
                        <dt class="w-32.5">より早く</dt>
                        <dd>Try it first</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-32.5">よりシンプルに</dt>
                        <dd>Simple is best</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-32.5">より楽しく</dt>
                        <dd>Have fun</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-32.5">環境に優しく</dt>
                        <dd>Go green</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-32.5">みんなを笑顔に</dt>
                        <dd>Make everyone happy</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</main>

@env(['local','staging'])<span id="test-flg-88">テスト</span>@endenv
</body>
</html>

