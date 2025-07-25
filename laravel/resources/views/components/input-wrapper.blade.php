@props(["id" => "input-name", "label", "type" => "text", "placeholder" => "", "value" => "", "options" => [], "readonly" => false, "required" => false])

<div {{ $attributes->merge(["class" => "input-wrapper"]) }}>
	@if ($label)
	<label for="{{ $id }}">{{ $label }}</label>
	@endif

	@if ($type === "textarea")
	<textarea id="{{ $id }}" name="{{ $id }}" placeholder="{{ $placeholder }}" @if($readonly) readonly @endif @if($required) required @endif>{{ $value }}</textarea>
	@elseif ($type === "select")
	<select name="{{ $id }}" id="{{ $id }}" @if($readonly) disabled @endif @if($required) required @endif>
		<option value="" disabled selected hidden>{{ $placeholder ?: "Pilih" }}</option>
			@foreach ($options as $optionValue => $optionLabel)
				<option value="{{ $optionValue }}" @if($value == $optionValue) selected @endif>
					{{ $optionLabel }}
				</option>
			@endforeach
	</select>
	@else
	<input type="{{ $type }}" id="{{ $id }}" name="{{ $id }}" placeholder="{{ $placeholder }}" value="{{ $value }}" @if($readonly) readonly @endif @if($required) required @endif>
	@endif
</div>