  <!doctype html>
  <html lang="en">
  <head>
    @include('layouts.head')
  </head>
  <body ng-init="username=null; password=null" ng-app="cscPortal">
    <div id="overlay"></div>


    <main class="w-[100dvw] h-[100dvh] popup popup-inverse">
     

    <form action="/dologin" method="post"  class="popup-wrapper border-t-4 border-green-600 w-96 flex flex-col">
        @csrf
        <div class="popup-body flex align-center gap-1 pb-0 text-green-700">
            <img src="{{asset('svg/logo.svg')}}" alt="logo" width="48">
            <div>
                <p class="font-size-2 text-body-600 font-bold">Department of Computer Science</p>
                <p class="font-size-1 text-body-400 font-semibold">Federal University of Technolog, Owerri</p>
            </div>
        </div>
        <div class="popup-body lg:!p-14">
    
          <div class="flex flex-col gap-1">        
          
            @error('login_info')
                <x-alert type="error">{{$message}}</x-alert>
            @enderror
    
            @if (request()->has('callbackUrl')) 
                <input type="hidden" name="callbackUrl" value="{{request()->callbackUrl}}"/>
            @endif
            <div class="flex flex-col gap-8">   
              <input type="email" ng-disabled="false" ng-model="username" id="username" name="usermail" placeholder="Username or Email"  class="input btn-lg" />
      
              <input type="password" ng-model="password" id="password" ng-model="password" name="password" placeholder="Password" class="input btn-lg" ng-disabled="!username" />
            </div>
            
              
    
            <div class="flex items-center justify-between text-xs">
              <div class="flex items-center gap-1">
                <input type="checkbox" class="checkbox peer" name="remember" id="remember" ng-disabled="!password" >
                <label for="remember" class="peer-disabled:opacity-30">Remember me.</label>
              </div>
    
              <a href="/lost-password" class="hover:underline">Forgot password?</a>
            </div>
    
            
    
          </div>
        </div>

        <div class="popup-footer">
          <button
              class="btn btn-primary transition"
              type="submit"
              ng-disabled="!password"
              >Sign in</button>
        </div>

      </form>
    </main>

    
    @include('layouts.footer')
    
    <img src="{{asset('svg/frame.svg')}}" alt="frame" class="absolute bottom-0 w-[350px] opacity-50 right-0">
  </body>
  <script>
    (function($) {
      $("form[action='/dologin']").on('submit', function(e) {
        return true;
        e.preventDefault();

        const formData = new FormData(this);

        const usermail = $('#username', this).val();
        const password = $('#password', this).val();

        
        

        
        api('/login', {usermail,password})
        .then(res=>{
          console.log(res);
          if ('token' in res) {
            localStorage.setItem('apiToken', res.token)
          }
          window.location.href = '/home';
        }).catch(e=>console.log(e));
      })
    })(jQuery)
  </script>
  </html>
