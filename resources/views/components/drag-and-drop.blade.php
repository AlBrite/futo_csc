@props(['name', 'alpine'])
@php 
if (!isset($alpine)) {
  $alpine = '';
}
@endphp
<div class="flex w-full justify-center">
  <div class="justify-self-center">
    <x-tooltip label="Choose or Drag Image here">
      <input type="file" id="fileInput" accepts="image/*" style="display: none;">
      <div id="dropZone" class="group drop-zone flex flex-col items-center rounded-full  justify-center">
          <img src="{{asset('svg/icons/image.svg')}}" {!!$alpine!!} class="w-full h-full object-cover absolute top-0 right-0"/>
          <p class="text-sm absolute p-1 bg-white hidden group-hover:block opacity-0 group-hover:opacity-50 transform">Drag & Drop image here</p>
      </div>
    </x-tooltip>
  </div>
</div>