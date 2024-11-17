<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0" />
    @include('partials.style')
  </head>
  <body class="bg-gray-100">
    @include('partials.header')

    @yield('content')

    @include('partials.footer')
  </body>
</html>
