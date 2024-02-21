@php 
  $students = \App\Models\Student::all();
  $results = \App\Models\Result::paginate(6);

  //dd($results);

@endphp 

<x-template title="Admin Dashboard" nav="home">

  <div class="md:flex flex-1"> 
      <div class="order-2 md:order-1 md:w-[40%]">
        <div class="scroller">
        <x-todo/>
          <div class="mx-4">
            <x-card class="mt-4">
              <div class="flex items-center gap-3">
                <img src="{{asset('images/user.jpg')}}" alt="Image" class="object-cover h-28 w-28 rounded-md"/>
                <div class="flex-1">

                  <table class="text-sm w-full border-collapse">

                    <tbody>
                      <tr>
                        <td>Name</td>
                        <td class="font-semibold">Bright Obi</td>
                      </tr>
                      <tr>
                        <td>Reg Number</td>
                        <td class="font-semibold">20181121075</td>
                      </tr>
                      <tr>
                        <td>Gender</td>
                        <td class="font-semibold">Male</td>
                      </tr>
                      <tr>
                        <td>Level</td>
                        <td class="font-semibold">500</td>
                      </tr>
                      <tr>
                        <td>CGPA</td>
                        <td class="font-semibold">5.0</td>
                      </tr>
                    </tbody>

                  </table>

                </div>

              </div>

            </x-card>
            <x-card class="mt-4">
              <div class="flex items-center gap-3">
                <img src="{{asset('images/user.jpg')}}" alt="Image" class="object-cover h-28 w-28 rounded-md"/>
                <div class="flex-1">

                  <table class="text-sm w-full border-collapse">

                    <tbody>
                      <tr>
                        <td>Name</td>
                        <td class="font-semibold">Bright Obi</td>
                      </tr>
                      <tr>
                        <td>Reg Number</td>
                        <td class="font-semibold">20181121075</td>
                      </tr>
                      <tr>
                        <td>Gender</td>
                        <td class="font-semibold">Male</td>
                      </tr>
                      <tr>
                        <td>Level</td>
                        <td class="font-semibold">500</td>
                      </tr>
                      <tr>
                        <td>CGPA</td>
                        <td class="font-semibold">5.0</td>
                      </tr>
                    </tbody>

                  </table>

                </div>

              </div>

            </x-card>
            <x-card class="mt-4">
              <div class="flex items-center gap-3">
                <img src="{{asset('images/user.jpg')}}" alt="Image" class="object-cover h-28 w-28 rounded-md"/>
                <div class="flex-1">

                  <table class="text-sm w-full border-collapse">

                    <tbody>
                      <tr>
                        <td>Name</td>
                        <td class="font-semibold">Bright Obi</td>
                      </tr>
                      <tr>
                        <td>Reg Number</td>
                        <td class="font-semibold">20181121075</td>
                      </tr>
                      <tr>
                        <td>Gender</td>
                        <td class="font-semibold">Male</td>
                      </tr>
                      <tr>
                        <td>Level</td>
                        <td class="font-semibold">500</td>
                      </tr>
                      <tr>
                        <td>CGPA</td>
                        <td class="font-semibold">5.0</td>
                      </tr>
                    </tbody>

                  </table>

                </div>

              </div>

            </x-card>
            
            
          </div>
            
        </div>
      </div>

      <div class="order-1 md:order-2 flex-1 flex flex-col">
        <div class="px-4 pt-4 scroller show-bar">

          <div class="grid mt-4 md:mt-0 gap-2 md:gap-5" id="dashboard-cards">

            <div class="group cursor-pointer flex h-20 overflow-hidden rounded shadow">
              <div class="h-20 w-20 bg-red-500 text-white flex items-center justify-center">
                <span class="material-symbols-rounded transform group-hover:scale-125">
                    groups
                </span>
              </div>
              <div class="flex-1 bg-white dark:bg-zinc-800   opacity-65 flex flex-col justify-center px-4">
                <div class="text-3xl">1544</div>
                <div class="">Students</div>

              </div>

            </div>

            <div class="group cursor-pointer flex h-20 overflow-hidden rounded shadow">
              <div class="h-20 w-20 bg-green-500 dark:opacity-60  text-white flex items-center justify-center">
                <span class="material-symbols-rounded transform group-hover:scale-125">
                    groups
                </span>
              </div>
              <div class="flex-1 bg-white dark:bg-zinc-800  opacity-65 flex flex-col justify-center px-4">
                <div class="text-3xl">1544</div>
                <div class="">Students</div>

              </div>

            </div>

            <div class="group cursor-pointer flex h-20 overflow-hidden rounded shadow">
              <div class="h-20 w-20 bg-blue-500 text-white flex items-center justify-center">
                <span class="material-symbols-rounded transform group-hover:scale-125">
                  auto_stories
                </span>
              </div>
              <div class="flex-1 bg-white dark:bg-zinc-800   opacity-65 flex flex-col justify-center px-4">
                <div class="text-3xl">100</div>
                <div class="">Semester Courses</div>

              </div>

            </div>

            <div class="group cursor-pointer flex h-20 overflow-hidden rounded shadow">
              <div class="h-20 w-20 bg-cyan-500 text-white flex items-center justify-center">
                <span class="material-symbols-rounded transform group-hover:scale-125">
                    bar_chart
                </span>
              </div>
              <div class="flex-1 bg-white dark:bg-zinc-800   opacity-65 flex flex-col justify-center px-4">
                <div class="text-3xl">199</div>
                <div class="">Results Uploaded</div>

              </div>

            </div>

                
          </div>

          <div class="flex flex-col shrink">
          <!--Notice Board -->
          <x-card class="shrink mt-4">
            <div class="notices px-2 md:overflow-y-auto">
              <div class="notice">
                <div class="flex justify-between">
                  <b class="link">Bright Ejimadu</b>
                  <span class="text-gray-600">24th Dec, 2023</span>
                </div>
                <div class="mt-1">
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque alias optio minus, vitae adipisci temporibus cum nesciunt eos explicabo minima accusantium dignissimos mollitia itaque et distinctio laudantium expedita tempore sapiente!
                </div>
              </div>

              <div class="notice mt-3">
                <div class="flex justify-between">
                  <b class="link">Bright Ejimadu</b>
                  <span class="text-gray-600">24th Dec, 2023</span>
                </div>
                <div class="mt-1">
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque alias optio minus, vitae adipisci temporibus cum nesciunt eos explicabo minima accusantium dignissimos mollitia itaque et distinctio laudantium expedita tempore sapiente!
                </div>
              </div>


              <div class="notice mt-3">
                <div class="flex justify-between">
                  <b class="link">Bright Ejimadu</b>
                  <span class="text-gray-600">24th Dec, 2023</span>
                </div>
                <div class="mt-1">
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque alias optio minus, vitae adipisci temporibus cum nesciunt eos explicabo minima accusantium dignissimos mollitia itaque et distinctio laudantium expedita tempore sapiente!
                </div>
              </div>
            </div>

          </x-card>
          <!--/Notice Board -->

          <x-card class="mt-4">
            <div class="mt-2 card overflow-x-auto max-w-full min-w-full">
                <table class="table table-auto min-w-full whitespace-nowrap">
                    <thead>
                        <th class="min-w-16"></th>
                        <th>Student Name</th>
                        <th>Reg. Number</th>
                        <th class="w-20">Level</th>
                        <th class="w-20">CGPA</th>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td align="center">
                                <x-profile-pic :user="$student" alt="student_pic" class="w-10 h-10 rounded-full object-cover"/>
                                </td>
                                <td>{{$student->user->name}}</td>
                                <td>{{$student->reg_no}}</td>
                                <td>{{$student->level}}</td>
                                <td>{{$student->cgpa}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
          </x-card>

          <div class="card">
            <h1 class="card-header text-body-300 font-semibold">Results Uploaded</h1>
            <div class="card-body">
              <div class="overflow-x-auto w-full min-w-full">
                  <table class="table table-auto min-w-full whitespace-nowrap">
                      <thead>
                          <th class="w-20">Course Code</th>
                          <th>Course Title</th>
                          <th class="w-20">Units</th>
                          <th class="w-20"></th>
                      </thead>
                      <tbody>
                          @foreach($results as $result)
                                  
                              <tr>
                                  <td class="uppercase">{{$result->course->code}}</td>
                                  <td>{{$result->course->name}}</td>
                                  <td>{{$result->course->unit}}</td>
                                  <td>
                                      <button
                                      class="text-xs font-semibold p-[.3rem] rounded text-white bg-[var(--primary)] hover:bg-[var(--primary-700)] transition" type="button">
                                          Download
                                      </button>
                                  </td>
                              </tr>
                          @endforeach
                          
                      </tbody>
                  </table>
              </div>

            </div>
          </div>
        </div>
      </div>
  </div>
</x-template>