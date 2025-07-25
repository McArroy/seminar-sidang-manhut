@props(["icon" => null, "iconclass" => "", "iconwidth" => ""])

@php
$classes = "button";
$iconHTML = ($icon ?? false)
	? "<iconify-icon icon='{$icon}' class='{$iconclass}' width='{$iconwidth}'></iconify-icon>"
	: "";
@endphp

<button {{ $attributes->merge(["class" => $classes]) }}>
	{!! $iconHTML !!}
	{{ $slot }}
</button>