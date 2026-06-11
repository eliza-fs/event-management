@props([
    'url' => null,
    'alt' => '',
    'hasImage' => false,
    'imgClass' => 'w-full h-full object-cover',
    'placeholderClass' => 'w-full h-full',
])

@if($hasImage && $url)
    <img src="{{ $url }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => $imgClass]) }} />
@else
    <div {{ $attributes->merge(['class' => $placeholderClass.' bg-gradient-to-br from-[#2F7F79]/25 to-[#2F7F79]/55 flex items-center justify-center']) }}>
        <span class="material-symbols-outlined text-white/80 text-5xl" style="font-variation-settings: 'FILL' 1">event</span>
    </div>
@endif
