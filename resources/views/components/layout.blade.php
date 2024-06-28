<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pixel Job</title>
    @Vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-black text-white">
    <div class="px-10">
        <nav class="flex justify-between items-center px-2 py-4 border-b border-white/10">
            <div>
                <a href="/">
                    <img src="{{Vite::asset('resources/images/logo.svg')}}" alt="">
                </a>
            </div>
            
            <div>
                @auth
                <div class="flex gap-4">
                    <a href="/jobs/create">
                     Post a Job
                    </a>
                    <form method="POST" action="/logout">
                        @csrf
                        @method('DELETE')
                     <button>Log Out</button>
                    </form>
                </div>  
                @endauth
            @guest
            <div class="space-x-6 font-bold">
                <a href="/register">Sign Up</a>
                <a href="/login">Log In</a>
            </div>
            @endguest
               
            </div>
        </nav>
        <main class="mt-10 max-w-[986px] mx-auto">
            {{$slot}}
        </main>
    </div>
</body>
</html>