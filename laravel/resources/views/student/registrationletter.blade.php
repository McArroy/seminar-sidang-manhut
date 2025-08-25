<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="/assets/css/pages/registrationform.css?v=1.0">
	@endsection

	@if (isset($_GET["mod"]) && $_GET["mod"] === "preview")
	@php $IsPreviewMode = true; @endphp
	@else
	@php $IsPreviewMode = false; @endphp
	@endif

	<x-slot name="title">{{ $IsPreviewMode ? "Pratinjau Formulir" : "Form Pendaftaran" }}</x-slot>
	<x-slot name="icon">basil:document-outline</x-slot>

	@if ($_GET["type"] === "seminar")

	@php $IsThesisDefense = false; @endphp

	@elseif ($_GET["type"] === "thesisdefense")

	@php $IsThesisDefense = true; @endphp

	@endif

	<x-slot name="pagetitle">{{ $IsPreviewMode ? "Pratinjau Formulir" : "Form Pendaftaran" }} {{ $IsThesisDefense ? "Sidang Akhir" : "Seminar" }}</x-slot>

	<div class="letter">
		@if (!$IsPreviewMode)
		<p>Berkas form pendaftaran {{ $IsThesisDefense ? "Sidang Akhir" : "Seminar" }} berhasil dibuat, harap <span>unduh</span> file berikut dan <span>lengkapi tanda tangan yang tertera</span>.</p>
		<p>Setelah berkas form pendaftaran {{ $IsThesisDefense ? "Sidang Akhir" : "Seminar" }} ditandatangani, <span>unggah kembali</span> di Menu <a href="{{ route('student.requirements', ['type' => $IsThesisDefense ? 'thesisdefense' : 'seminar']) }}">Persyaratan {{ $IsThesisDefense ? "Sidang" : "Seminar" }}</a> bersama berkas pendukung lainnya.</p>
		@endif

		<x-letter :data="$data" :IsThesisDefense="$IsThesisDefense"></x-letter>
	</div>
</x-app-layout>