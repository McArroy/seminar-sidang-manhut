@php
	if (!in_array($_GET["type"] ?? null, ["seminar", "thesisdefense"]))
	{
		header("Location: " . url()->current() . "?type=seminar");
		exit;
	}
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="/assets/css/pages/registrationform.css?v=1.0">
	@endsection

	<x-slot name="title">Form Pendaftaran</x-slot>
	<x-slot name="icon">basil:document-outline</x-slot>

	@if ($_GET["type"] === "seminar")

	@php $IsThesisDefense = false; @endphp

	<x-slot name="pagetitle">Form Pendafataran Seminar</x-slot>

	@elseif ($_GET["type"] === "thesisdefense")

	@php $IsThesisDefense = true; @endphp

	<x-slot name="pagetitle">Form Pendafataran Sidang Akhir</x-slot>

	@endif

	<div class="letter">
		<p>Berkas form pendaftaran {{ $IsThesisDefense ? "sidang akhir" : "seminar" }} berhasil dibuat, harap <span>download</span> file berikut dan <span>lengkapi tanda tangan yang tertera</span>.</p>
		<p>Setelah berkas form pendaftaran {{ $IsThesisDefense ? "sidang akhir" : "seminar" }} ditandatangani, <span>upload kembali</span> di Menu <span>Persyaratan {{ $IsThesisDefense ? "Sidang" : "Seminar" }}</span> bersama berkas pendukung lainnya.</p>
		<x-letter :data="$data" :IsThesisDefense="$IsThesisDefense"></x-letter>
	</div>
</x-app-layout>