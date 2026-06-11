@props(['class' => 'h-8 w-8 object-contain', 'textClass' => 'text-white text-xl font-bold'])

<a href="{{ $href ?? route('home') }}" {{ $attributes->merge(['class' => 'flex items-center gap-3 shrink-0']) }}>
    <img src="{{ $appLogoUrl ?? config('volunteerhub.logo_url') }}" alt="VolunteerHub" class="{{ $class }}"/>
    @if($showText ?? true)
        <span class="{{ $textClass }} leading-tight tracking-tight">VolunteerHub</span>
    @endif
</a>
