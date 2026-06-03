@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-slate-300']) }}>
    {{ $value ?? $slot }}
</label>
