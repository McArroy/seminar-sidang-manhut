@php
	$InputSearch = $_GET["search"] ?? "";
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="/assets/css/pages/datatables.css?v=1.0">
	@endsection

	<x-slot name="icon">fluent:form-28-regular</x-slot>

	@if ($_GET["type"] === "seminar")

	<x-slot name="title">Pengumuman Seminar</x-slot>
	<x-slot name="pagetitle">Pengumuman Seminar</x-slot>

	@elseif ($_GET["type"] === "thesisdefense")

	<x-slot name="title">Undangan Sidang</x-slot>
	<x-slot name="pagetitle">Undangan Sidang</x-slot>

	@endif

	<div class="top">
		<x-input-wrapper class="search type-2" id="search" type="text" placeholder="Cari Data" value="{{ $InputSearch }}" autofocus />
	</div>
	<div class="middle">
		<table class="type-2">
			<thead>
				<tr>
					<th class="numbered">No</th>
					<th class="number">NIM</th>
					<th class="name">Nama</th>
					<th class="title">Judul</th>
					<th class="form">Form {{ request()->query("type") === "seminar" ? "Undangan" : "Pengumuman" }}</th>
					<th class="form">Cetak {{ request()->query("type") === "seminar" ? "Undangan" : "Pengumuman" }}</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($dataSubmissions as $index => $item)
				<tr>
					<td class="numbered"></td>
					<td class="number">{{ strtoupper($item->useridnumber) }}</td>
					<td class="name">{!! $item->username ?? "<i>Data Mahasiswa<br>Tidak Ditemukan</i>" !!}</td>
					<td class="title">{{ $item->title }}</td>
					<td class="form">
						<x-button class="form" id="add-form-letter" icon="system-uicons:create" iconwidth="23" data-link="{{ request()->query('type') === 'seminar' ? $item->seminarid : $item->thesisdefenseid }}">Isi Form</x-button>
					</td>
					<td class="form">
						<x-button class="print" id="print-form-letter" icon="material-symbols-light:print-outline-rounded" iconwidth="23" data-link="{{ request()->query('type') === 'seminar' ? $item->seminarid : $item->thesisdefenseid }}">Cetak</x-button>
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="6" class="not-found">Tidak Ada Data Yang Ditemukan</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>

	<x-navigator-buttons :data="$dataSubmissions" />
</x-app-layout>