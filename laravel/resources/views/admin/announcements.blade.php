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
	
	<script>
		$(document).on("click", "button#add-form-letter", function()
		{
			const $id = $(this).data("link");
			const $optionsHtml =
			`
			{!!
				collect($dataLecturers)->map(function($lecturer)
				{
					return '<option value="' . strtoupper($lecturer->useridnumber) . '">' . e($lecturer->username) . '</option>';
				})->implode('')
			!!}`;
			let $innerContent =
			`
				<x-input-wrapper id="number_letter" type="text" label="Nomor Surat" placeholder="Masukkan Nomor Surat" required />
				<x-input-wrapper id="moderator" label="Moderator" type="select" placeholder="Pilih Dosen Moderator" required>
					${$optionsHtml}
				</x-input-wrapper>
				<x-input-wrapper id="date_letter" type="date" label="Tanggal Pembuatan" required />
			`;

			@php
				$type = request()->query("type");
				$path = $type === "thesisdefense"
					? route("admin.announcements.thesisdefense.add", ":id")
					: route("admin.announcements.seminar.add", ":id");
				$title = $type === "thesisdefense"
					? "Undangan Sidang Akhir"
					: "Pengumuman Seminar";
			@endphp

			@if ($type === "thesisdefense")
				$innerContent +=
				`
					<x-input-wrapper id="supervisory_committee" label="Ketua Komisi Pembimbing" type="select" placeholder="Pilih Ketua Komisi Pembimbing" required>
						${$optionsHtml}
					</x-input-wrapper>
					<x-input-wrapper id="external_examiner" label="Penguji Luar Komisi" type="select" placeholder="Pilih Penguji Luar Komisi" required>
						${$optionsHtml}
					</x-input-wrapper>
					<x-input-wrapper id="chairman_session" label="Ketua Sidang" type="select" placeholder="Pilih Ketua Sidang" required>
						${$optionsHtml}
					</x-input-wrapper>
				`;
			@endif

			return DialogInputData("{{ $path }}".replace(":id", $id), "heroicons:user-group-solid", {!! json_encode($title) !!}, "POST", $innerContent);
		});
	</script>
</x-app-layout>