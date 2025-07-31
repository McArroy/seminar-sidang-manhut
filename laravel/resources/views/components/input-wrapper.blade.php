@props(["id" => "input-name", "label" => null, "type" => "text", "placeholder" => "", "value" => "", "options" => [], "oninput" => null, "onchange" => null, "readonly" => false, "required" => false, "autofocus" => false])

<div {{ $attributes->merge(["class" => "input-wrapper"]) }}>
	@if ($label)
	<label for="{{ $id }}">{{ $label }}</label>
	@endif

	@if ($type === "textarea" || $type === "url")
	<textarea id="{{ $id }}" name="{{ $id }}" placeholder="{{ $placeholder }}" oninput="{{ $oninput }}" onchange="{{ $onchange }}" @if($readonly) readonly @endif @if($required) required @endif @if($autofocus) autofocus @endif>{{ $value }}</textarea>
	@elseif ($type === "select")
	<select name="{{ $id }}" id="{{ $id }}" oninput="{{ $oninput }}" onchange="{{ $onchange }}" @if($readonly) disabled @endif @if($required) required @endif @if($autofocus) autofocus @endif>
		<option value="" disabled selected hidden>{{ $placeholder ?: "Pilih" }}</option>
		@if (!empty($options))
			@foreach ($options as $optionValue => $optionLabel)
				<option value="{{ $optionValue }}" @if($value == $optionValue) selected @endif>
					{{ $optionLabel }}
				</option>
			@endforeach
		@else
			{{ $slot }}
		@endif
	</select>
	@else
	<input type="{{ $type }}" id="{{ $id }}" name="{{ $id }}" placeholder="{{ $placeholder }}" oninput="{{ $oninput }}" onchange="{{ $onchange }}" value="{{ $value }}" @if($readonly) readonly @endif @if($required) required @endif @if($autofocus) autofocus @endif>
	@endif

	@if ($type !== "select" && empty($options))
		{{ $slot }}
	@endif
</div>