<x-template>
  <script>
    function select(value) {
      alert('I selected '+value);
    }
  </script>

  <x-checkbox name="gender" value='Male'>Male</x-checkbox>
  <x-radio name="gender" value='Female'>Female</x-radio>
 <br>
 <br>
 <br>
 <br>

  

  <div class="flex justify-start">
    <x-dropdown name="courses[]" placeholder="Gender">
    <x-option value="male">Male</x-option>
    <x-option value="female">Female</x-option>
  </x-dropdown></div>


</x-template> 