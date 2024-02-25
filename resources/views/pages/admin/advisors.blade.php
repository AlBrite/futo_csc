
@php 
  
  $advisors = \App\Models\Advisor::all();
  $results = \App\Models\Result::paginate(6);
  
  $advisor_id = request()->advisor_id;
  if (!is_numeric($advisor_id)) {
    $advisor_id = 'null';
  }

@endphp
<x-template nav="advisors" title="Admin - Advisors Manager">
  <div data-init="advisor_id={{$advisor_id}}" ng-controller="AdvisorController" class="md:flex justify-between items-stretch max-h-full min-h-full overflow-hidden">
    <div ng-class="{'hidden md:block': advisor}" class="md:w-[380px]">
      <div>
        <form class="flex items-center justify-between gap-2 w-full flex-wrap bg-green-50 p-5">
          
          <div class="flex-1">
            <input type="search" class="input w-full" placeholder="Enter Advisor's Name"/>
          </div>
              
        </form>
        
        <div class="scroller">
          <div class="flex flex-col h-full  rounded-md">
            <table class="border-collapse">
              <tbody>
                @foreach($advisors as $advisor)
                  <tr advisor_id="{{$advisor->id}}" ng-click="displayAdvisor({{$advisor->id}})" class="hover:bg-green-100 cursor-pointer gap-3 border-b border-slate-200 last:border-transparent p-5 items-center">
                    <td align="center">
                      <img src="{{$advisor->picture()}}" alt="PIC" class="w-10 h-10 rounded-full object-cover"/>
                    </td>
                    <td>
                      {{$advisor->user->name}}
                    </td>
                    <td class="text-center">
                      ID<br>
                      ADV-000{{$advisor->user->unique_id}}
                    </td>
                    <td class="text-center">
                      Students<br>
                      1000
                    </td>
                  </tr>
                  
                @endforeach
              </tbody>
            </table>
        </div>

      </div>
    </div>
  </div>
  <div class="md:flex-1 md:bg-green-50/10">
    <div class="scroller">
      <div class="flex justify-between items-center">
        <div class="md:invisible flex items-center cursor-pointer"  x-on:click="back">
          <span class="material-symbols-rounded">
            arrow_back
          </span>
          <span>Back</span>
        </div>

        <div>
          <button class="btn-white top-0 sticky" ng-click="addAdvisor=true">Add Advisor</button>
        </div>

        
      </div>
      <!--add course-->
      @include('popups.add-advisor')
      <!--/add course-->
      @include('popups.view-advisor')
    </div>
  
  </div>
    
</x-template>
<style>
body {
  background: #f0fdf4;
}
</style>
  <script>
      function init() {
       
        if (this.advisor_id) {

          api('/advisor', {advisor_id:this.advisor_id})
          .then(response => {
  
            this.advisor = response
          })
          .catch(error => console.log(error));

        }

        
      }
  </script>