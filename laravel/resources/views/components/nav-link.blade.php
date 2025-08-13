@props(["href" => "javascript:void(0);", "target" => "_self", "icon" => null, "iconclass" => "", "iconwidth" => "", "active"])

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

<a {{ $attributes->merge(["href" => $href, "target" => $target, "class" => $classes]) }}>
	@if ($icon)
	<iconify-icon icon="{{ $icon }}" class="{{ $iconclass }}" width="{{ $iconwidth }}"></iconify-icon>
	@endif

	<div class="text">{{ $slot }}</div>

	@if ($IsButtonList)
	<iconify-icon class="arrow" icon="weui:arrow-filled" width="12"></iconify-icon>
	@endif
</a>