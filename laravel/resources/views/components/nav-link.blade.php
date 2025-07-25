@props(["href" => "javascript:void(0);", "active"])

@php
$href = ($active ?? false)
	? "javascript:void(0);"
	: $href;

$OriginalClass = $attributes->get("class");

$IsButtonList = str_contains($OriginalClass, "button-list");

$classes = ($active ?? false)
	? "button active" . ($IsButtonList ? " listed" : "")
	: "button";
@endphp

<a {{ $attributes->merge(["href" => $href, "class" => $classes]) }}>
	{{ $slot }}
</a>