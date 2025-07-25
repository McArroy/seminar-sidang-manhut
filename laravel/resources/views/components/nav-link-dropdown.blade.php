@props(["active"])

@php
$classes = ($active ?? false)
	? "button-list-wrapper active"
	: "button-list-wrapper";
@endphp

<div {{ $attributes->merge(["class" => $classes]) }}>
	{{ $slot }}
</div>