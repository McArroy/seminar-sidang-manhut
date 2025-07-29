@props(["href" => null, "target" => "_self", "icon" => null, "iconclass" => "", "iconwidth" => ""])

@php
$classes = "button";
$onclick = $href ? "window.open('{$href}', '{$target}')" : null;
@endphp

<button {{ $attributes->merge(["class" => $classes]) }} @if ($onclick) onclick="{{ $onclick }}" @endif>
	@if ($icon)
	<iconify-icon icon="{{ $icon }}" class="{{ $iconclass }}" width="{{ $iconwidth }}"></iconify-icon>
	@endif

	{{ $slot }}
</button>