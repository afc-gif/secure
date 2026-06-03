@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-lg border-white/10 bg-black/25 text-slate-100 shadow-inner shadow-black/20 placeholder:text-slate-500 focus:border-[#f35aa5] focus:ring-[#f35aa5]']) }}>
