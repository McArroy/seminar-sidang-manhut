<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="/assets/css/pages/seminarflow.css?v=1.0">
	@endsection

	@section("activate-navbar", "active")

	@php
		if (!in_array($_GET["type"] ?? null, ["seminar", "thesisdefense"]))
		{
			header("Location: " . url()->current() . "?type=seminar");
			exit;
		}
	@endphp

	@if ($_GET["type"] === "seminar")

	<x-slot name="title">Daftar Seminar</x-slot>
	<x-slot name="icon">fluent:form-28-regular</x-slot>
	<x-slot name="pagetitle">Daftar Seminar</x-slot>

	<img src="/assets/img/new-seminar-flow.jpg" alt="seminar-flow-image">

	@elseif ($_GET["type"] === "thesisdefense")

	<x-slot name="title">Daftar Sidang Akhir</x-slot>
	<x-slot name="icon">streamline-flex:presentation</x-slot>
	<x-slot name="pagetitle">Daftar Sidang Akhir</x-slot>

	<img src="/assets/img/new-thesis-defence-flow.jpg" alt="thesisdefense-flow-image">

	@endif
</x-app-layout>