@props(["id" => "input-name", "label" => null, "type" => "text", "placeholder" => "", "value" => "", "options" => [], "oninput" => null, "onchange" => null, "readonly" => false, "required" => false, "checked" => false, "autofocus" => false, "loading" => false])

<div {{ $attributes->merge(["class" => "input-wrapper"]) }}>
	@if ($label)
	<label for="{{ $id }}">{{ $label }}</label>
	@endif

	@if ($type === "textarea" || $type === "url")
	<textarea id="{{ $id }}" class="{{ $loading ? 'loading' : '' }}" name="{{ $id }}" placeholder="{{ $placeholder }}" oninput="{{ $oninput }}" onchange="{{ $onchange }}" @if($readonly || $loading) readonly @endif @if($required) required @endif @if($autofocus) autofocus @endif>{{ $value }}</textarea>
	@elseif ($type === "select" || $type === "select2")
	<select id="{{ $id }}" class="{{ $type === 'select2' ? 'select2 ' : '' }} {{ $loading ? 'loading' : '' }}" name="{{ $id }}" oninput="{{ $oninput }}" onchange="{{ $onchange }}" @if($readonly || $loading) disabled @endif @if($required) required @endif @if($autofocus) autofocus @endif>
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
	<input type="{{ $type }}" id="{{ $id }}" class="{{ $loading ? 'loading' : '' }}" name="{{ $id }}" placeholder="{{ $placeholder }}" oninput="{{ $oninput }}" onchange="{{ $onchange }}" value="{{ $value }}" @if($readonly || $loading) readonly @endif @if($required) required @endif @if($checked) checked @endif @if($autofocus) autofocus @endif>
	@endif

	@if ($type !== "select" && empty($options))
		{{ $slot }}
	@endif
</div>