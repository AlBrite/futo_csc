@props(['label', 'direction', 'relative'])
@php 
$relative_attr = '';
if (isset($relative)) {
  $relative_attr = "data-tooltip-relative='$relative'";
}
@endphp

<span class="tooltip">
  {{$slot}}
  <span class="tooltip-label dir-{{isset($direction)?$direction:'bottom'}}" {!! $relative_attr !!}>{{$label}}</span>
</span>