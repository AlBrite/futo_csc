@props(['title', 'nav', 'data', 'module'])
@php 

  use Illuminate\Support\Arr;
  $htmlClass = Cookie::get('darkMode') === 'true' ? 'dark':'';

  $defaults = ['title'=>'CSC Admin Portal', 'nav'=>'', 'data'=>''];
  foreach($defaults as $default=>$value) {
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
@endphp 
<!DOCTYPE html>
<html ng-cloak ng-app="myapp" lang="en" x-bind:class="{'dark': darkMode}" x-data="{ {{$data}} showFullSideBar: false, darkMode: localStorage.getItem('darkMode')==='true', toggleTheme() { this.darkMode = !this.darkMode;localStorage.setItem('darkMode', this.darkMode);} }"  @resize.window="handleResize" class="{{$htmlClass}}">
<head>
  @include('layouts.head', ['title'=>$title])
  
</head>
<body class="page-{{$role}} h-screen select-none">
<div id="overlay"  class="dark:bg-black hidden" :xclass="{'flex':courseOpen||open|overlay}">
    <img src="{{asset('svg/logo.svg')}}" alt="">
    <div class="spinner"> </div>
    <noscript>
      <style>
        #overlay img, #overlay .spinner {display:none}
        #overlay {
          background-color: rgb(247,250,252);
        }
      </style>
      <span class="uppercase text-gray-500 tracking-wider text-lg">
        You need to enable your javascript to access this site
      </span>
    </noscript>
  </div>

    @include('partials.alerts')




  <div class="lg:flex items-stretch h-screen relative">

    @include('layouts.menu', compact('nav', 'role'))

    <div class="lg:flex flex-1 flex-col">
      @include('layouts.header')
      <main class="lg:flex flex-1 flex-col" id="main-slot">
         
          {{$slot}}

      </main>
  </div>
</body>
@include('layouts.footer');
@stack('scripts')
</html>