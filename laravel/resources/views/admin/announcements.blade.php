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
		<x-input-wrapper class="search type-2" id="search" type="text" placeholder="Cari Data {{ request()->query('type') === 'seminar' ? 'Seminar' : 'Sidang Akhir' }}" value="{{ $InputSearch }}" autofocus />
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
	
	<script>
		@php
		$dataLecturers = $dataLecturers->pluck('username', 'useridnumber')->mapWithKeys(fn($v, $k) => [strtoupper($k) => $v])->toArray();
		@endphp
		
		$(document).on("click", "button#add-form-letter", function()
		{
			const $id = $(this).data("link");
			let $innerContent =
			`
				<x-input-wrapper id="number_letter" type="text" label="Nomor Surat" placeholder="Masukkan Nomor Surat" required />
				<x-input-wrapper id="moderator" label="Moderator" type="select2" placeholder="Pilih Dosen Moderator" :options="$dataLecturers" required />
				<x-input-wrapper id="date_letter" type="date" label="Tanggal Pembuatan" required />
			`;

			@php
				$type = request()->query("type");
				$path = $type === "thesisdefense"
					? route("admin.announcements.thesisdefense.add", ":id")
					: route("admin.announcements.seminar.add", ":id");
			@endphp

			@if ($type === "thesisdefense")
				$innerContent +=
				`
					<x-input-wrapper id="supervisory_committee" label="Ketua Komisi Pembimbing" type="select2" placeholder="Pilih Ketua Komisi Pembimbing" :options="$dataLecturers" required />
					<x-input-wrapper id="external_examiner" label="Penguji Luar Komisi" type="select2" placeholder="Pilih Penguji Luar Komisi" :options="$dataLecturers" required />
					<x-input-wrapper id="chairman_session" label="Ketua Sidang" type="select2" placeholder="Pilih Ketua Sidang" :options="$dataLecturers" required />
				`;
			@endif

			return DialogInputData("{{ $path }}".replace(":id", $id), "Buat", "POST", $innerContent);
		});
	</script>
</x-app-layout>