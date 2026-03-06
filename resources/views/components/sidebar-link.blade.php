@props(['active', 'icon'])

@php
  $classes = ($active ?? false)
    ? 'flex items-center gap-3 px-4 py-3 mt-1 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg group transition-colors'
    : 'flex items-center gap-3 px-4 py-3 mt-1 text-sm font-medium text-gray-600 rounded-lg hover:text-gray-900 hover:bg-gray-100 group transition-colors';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
  <div class="{{ ($active ?? false) ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }} transition-colors">
    {!! $icon ?? '' !!}
  </div>
  {{ $slot }}
</a>