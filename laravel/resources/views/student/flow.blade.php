@php
	$queryType = request()->query("type") ?? "";
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="{{ \App\Http\Controllers\HelperController::Asset('assets/css/pages/seminarflow.css') }}">
	@endsection

	@if ($queryType === "seminar")

	<x-slot name="title">Daftar Seminar</x-slot>
	<x-slot name="icon">fluent:form-28-regular</x-slot>
	<x-slot name="pagetitle">Daftar Seminar</x-slot>

	<img src="{{ \App\Http\Controllers\HelperController::Asset('assets/img/seminar-flow.jpg') }}" alt="seminar-flow-image">

	@elseif ($queryType === "thesisdefense")

	<x-slot name="title">Daftar Sidang Akhir</x-slot>
	<x-slot name="icon">streamline-flex:presentation</x-slot>
	<x-slot name="pagetitle">Daftar Sidang Akhir</x-slot>

	<img src="{{ \App\Http\Controllers\HelperController::Asset('assets/img/thesisdefense-flow.jpg') }}" alt="thesisdefense-flow-image">

	@endif
</x-app-layout>