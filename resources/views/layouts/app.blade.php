<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0" />
    @include('partials.style')
  </head>
  @vite('resources/js/app.js')
  <body class="bg-gray-100">
    @include('partials.header')

    @if (session('success'))
        <div class="text-center font-bold bg-green-150 w-64 p-2 my-3 rounded-md m-auto text-green-700">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="text-center font-bold bg-red-150 w-64 p-2 my-3 rounded-md m-auto text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div id="app">
      @yield('content')
    </div>

    @include('partials.footer')
  </body>
</html>
