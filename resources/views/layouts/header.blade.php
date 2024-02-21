@php $profile = \App\Models\User::active(); @endphp
<header id="mainheader">
    <div class="flex items-center">
        <span
            class="sidebar-toggler material-symbols-rounded text-body-800 cursor-pointer hover:text-[var(--primary-700)] transition">
            menu
        </span>
        <div class="login--top flex items-center gap-1 text-green-700">
            <img src="{{asset('svg/logo.svg')}}" alt="logo" width="30">
            <div class="text-xm hidden lg:block text-sm">
                <p class="font-size-2 text-body-600 dark:text-white font-bold relative -bottom-[2px]">Department of Computer Science</p>
                <p class="font-size-1 text-body-400 dark:text-white/60 font-semibold  relative -top-[2px]">Federal University of Technolog, Owerri</p>
            </div>
        </div>
    </div>

    <div class="flex-1 flex justify-end items-center">
      

      <div class="flex items-center gap-1">
          <span>
          <div class="flex flex-col justify-center ml-3">
              <label class=" cursor-pointer p-2" x-on:click="toggleTheme()">
                  <svg x-show="!darkMode"  width="16" height="16" xmlns="http://www.w3.org/2000/svg">
                      <path class="fill-slate-300" d="M7 0h2v2H7zM12.88 1.637l1.414 1.415-1.415 1.413-1.413-1.414zM14 7h2v2h-2zM12.95 14.433l-1.414-1.413 1.413-1.415 1.415 1.414zM7 14h2v2H7zM2.98 14.364l-1.413-1.415 1.414-1.414 1.414 1.415zM0 7h2v2H0zM3.05 1.706 4.463 3.12 3.05 4.535 1.636 3.12z" />
                      <path class="fill-slate-400" d="M8 4C5.8 4 4 5.8 4 8s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4Z" />
                  </svg>
                  <svg x-show="darkMode" width="16" height="16" xmlns="http://www.w3.org/2000/svg">
                      <path class="fill-slate-400" d="M6.2 1C3.2 1.8 1 4.6 1 7.9 1 11.8 4.2 15 8.1 15c3.3 0 6-2.2 6.9-5.2C9.7 11.2 4.8 6.3 6.2 1Z" />
                      <path class="fill-slate-500" d="M12.5 5a.625.625 0 0 1-.625-.625 1.252 1.252 0 0 0-1.25-1.25.625.625 0 1 1 0-1.25 1.252 1.252 0 0 0 1.25-1.25.625.625 0 1 1 1.25 0c.001.69.56 1.249 1.25 1.25a.625.625 0 1 1 0 1.25c-.69.001-1.249.56-1.25 1.25A.625.625 0 0 1 12.5 5Z" />
                  </svg>
                  <span class="sr-only">Switch to light / dark version</span>
              </label>
          </div>
      </span>
      <x-profile-pic :user="$profile"  alt="user_img" class="w-7 h-7 object-cover rounded-full"/>


      </div>
  </div>
</header>