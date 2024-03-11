@php
  //  dd($_COOKIE);
@endphp
<!doctype html>
  <html lang="en" ng-cloak ng-app="cscPortal" ng-controller="RootController" ng-class="{'dark': darkMode}"
      ng-resize="handleResize()" ng-init="init()">

  <head>
      @include('layouts.head')
  </head>

  <body ng-init="username=null; password=null" ng-app="cscPortal">
      <div id="overlay"></div>


      <main class="w-[100dvw] h-[100dvh] popup popup-inverse">


          <form ng-controller="AuthController" method="post" class="popup-wrapper border-t-4 border-green-600 w-96 flex flex-col">
              @csrf
              <div class="popup-body bg-primary bg-cover login-header font-[800]" style=" background-position:0px -180px;background-image:url('{{asset('images/laptop.jpg')}}');">
                
                <div class="bg-cover bg-center flex flex-col lg:flex-row justify-center lg:justify-start items-center gap-2 lg:gap-5 pb-0 text-white">
                  <img src="{{ asset('svg/logo.svg') }}" alt="logo" width="48">
                  <div>
                      <p class="font-size-2 text-body-600 font-bold">Department of Computer Science</p>
                      <p class="font-size-1 text-body-400 font-semibold">Federal University of Technology, Owerri</p>
                  </div>
                </div>
              </div>
              <div class="flex-1 overflow-y-auto p-2 lg:p-5">

                <fieldset class="py-4 lg:p-5 font-bold text-center border-t border-gray-200">
                  <legend class="px-3 text-black dark:text-white opacity-25">LOG IN AREA</legend>

                  <div class="flex flex-col gap-1">

                      @error('login_info')
                          <x-alert type="error">{{ $message }}</x-alert>
                      @enderror

                      @if (request()->has('callbackUrl'))
                          <input type="hidden" name="callbackUrl" value="{{ request()->callbackUrl }}" />
                      @endif
                      <div class="flex flex-col gap-5 px-4">
                          <x-input autofocus="true" leading="<span class='material-symbols-rounded'>lock</span>" type="text"
                              ng-disabled="false" ng-model="credential" id="credential" name="credential"
                              placeholder="Username or Email" class="input btn-lg" />

                          <x-input leading="<span class='material-symbols-rounded'>account_circle</span>"
                              type="password" ng-model="password" id="password" ng-model="password" name="password"
                              placeholder="Password" class="input btn-lg" />

                          

                          <div class="flex items-center justify-between text-xs">
                              <div class="flex items-center gap-1">
                                  <x-checkbox class="checkbox peer" ng-model="remember" name="remember" id="remember"
                                      >
                                      Remember me.
                                  </x-checkbox>
                              </div>

                              <a href="/lost-password" class="hover:underline">Forgot password?</a>
                          </div>

                          <button class="btn btn-primary transition" type="submit" ng-click="login($event)">Sign in</button>

                      </div>







                  </div>
                </fieldset>
              </div>

          </form>
      </main>


      @include('layouts.footer')

      <img src="{{ asset('svg/frame.svg') }}" alt="frame" class="absolute bottom-0 w-[350px] opacity-50 right-0">
  </body>
  @include('layouts.footer')

  </html>
