<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('includes.meta')
    @yield('meta')
</head>
<body class="bg-slate-50">
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
                            <input class="!px-[10px] w-full !border-0 !border-b !border-slate-300 !bg-slate-100 placeholder:text-slate-400 h-11.5 focus:!border-slate-400 focus:!bg-sky-50 focus:shadow-inner focus:ring-0" type="email" name="email" value="{{old('email')}}" required autofocus autocomplete="username" placeholder="ID">
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
                            <input class="!px-[10px] w-full !border-0 !border-b !border-slate-300 !bg-slate-100 placeholder:text-slate-400 h-11.5 focus:!border-slate-400 focus:!bg-sky-50 focus:shadow-inner focus:ring-0" type="password" name="password" required autocomplete="current-password" placeholder="password">
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

                <button type="submit" class="w-full rounded-sm text-base font-bold text-white h-12.5 bg-orange hover:bg-opacity-80">ログイン</button>
            </div>
        </form>
        <div class="mb-[30px]"><img class="w-[300px]" src="/images/logo.svg" alt="バースストーリー" /></div>
    </div>
</main>

@env(['local','staging'])<span id="test-flg-88">テスト</span>@endenv
</body>
</html>

