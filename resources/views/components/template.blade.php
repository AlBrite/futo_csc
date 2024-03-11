@props(['title', 'nav', 'data', 'module', 'minimize'])
@php
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Arr;

    $htmlClass = Cookie::get('darkMode') === 'true' ? 'dark' : '';

    $defaults = ['title' => 'CSC Admin Portal', 'nav' => '', 'data' => ''];
    foreach ($defaults as $default => $value) {
        if (!isset($$default)) {
            $$default = $value;
        }
    }
    if (strlen($data) > 0) {
        $data .= ',';
    }

    if (!isset($module)) {
        $module = $nav;
    }

    $role = 'guest';

    if (auth()->check()) {
        $role = auth()->user()->role;
    }
    $active_nav = $nav;
    if (isset($active)) {
        $active_nav = $active;
    }

    if (!isset($minimize)) {
        $minimize = false;
    }

@endphp
<!DOCTYPE html>
<html lang="en" ng-cloak ng-app="cscPortal" ng-controller="RootController" ng-class="{'dark': darkMode}"
    ng-resize="handleResize()" class="{{ $htmlClass }}" ng-init="init()" custom-on-change>

<head>
    @include('layouts.head', ['title' => $title])

</head>

<body class="page-{{ $role }} select-none">
    @include('partials.popup-alert')

    <x-overlay />




    <div class="lg:flex items-stretch h-screen relative">

        @include('layouts.aside', compact('nav', 'role', 'minimize'))

        <div class="lg:flex flex-1 flex-col h-full">
            @include('layouts.header')
            <main id="main-slot">
                

                {{ $slot }}

            </main>
        </div>
</body>
@include('layouts.footer')

</html>
