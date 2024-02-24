  <!doctype html>
  <html lang="en">
  <head>
    @include('layouts.head')
  </head>
  <body x-data="{username:'', password:''}">
    <div id="overlay"></div>

    <main class="w-[100dvw] h-[100dvh] flex flex-col justify-center items-center">
     

      <div class="min-h-[26rem] bg-white/50 border-t-4 border-green-600 rounded-lg w-96 flex flex-col gap-10 lg:shadow-lg lg:p-5  justify-center relative z-20  p-8">

        <div class="login--top flex align-center gap-1 p-x-1 pt-2 text-green-700">
            <img src="{{asset('svg/logo.svg')}}" alt="logo" width="48">
            <div>
                <p class="font-size-2 text-body-600 font-bold">Department of Computer Science</p>
                <p class="font-size-1 text-body-400 font-semibold">Federal University of Technolog, Owerri</p>
            </div>
        </div>

        <form action="/dologin" method="post" id="login-form" class="flex flex-col gap-6 text-body-400">
          @csrf
          @error('login_info')
              <x-alert type="error">{{$message}}</x-alert>
          @enderror

          @if (request()->has('callbackUrl')) 
              <input type="hidden" name="callbackUrl" value="{{request()->callbackUrl}}"/>
          @endif
          

          


          
          <input type="text" id="username" name="usermail" placeholder="Username or Email"  class="input btn-lg"  x-on:keyup="username=$el.value"/>

          <input type="password" id="password" name="password" placeholder="Password" class="input btn-lg" x-on:keyup="password=$el.value"/>
            

          <div class="flex items-center justify-between">
            <div class="flex items-center gap-1">
              <input type="checkbox" class="checkbox" name="remember" id="remember">
              <label for="remember">Remember me.</label>
            </div>

            <a href="/lostpassword" class="hover:underline">Forgot password?</a>
          </div>

          <button
          class="btn-primary transition"
          type="submit"
          :disabled="!username||!password"
          disabled>Sign in</button>

        </form>
      </div>
    </main>
    @include('layouts.footer')
    
    <img src="http://127.0.0.1:8000/svg/frame.svg" alt="frame" class="absolute bottom-0 w-[350px] opacity-50 right-0">
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
