@php
	$querySearch = request()->query("search") ?? "";
	$queryType = request()->query("type") ?? "";
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="/assets/css/pages/datatables.css?v=1.0">
	@endsection

	<x-slot name="icon">fluent:form-28-regular</x-slot>

	@if ($queryType === "seminar")

	<x-slot name="title">Pengumuman Seminar</x-slot>
	<x-slot name="pagetitle">Pengumuman Seminar</x-slot>

	@elseif ($queryType === "thesisdefense")

	<x-slot name="title">Undangan Sidang</x-slot>
	<x-slot name="pagetitle">Undangan Sidang</x-slot>

	@endif

	<div class="top">
		<x-input-wrapper class="search type-2" id="search" type="text" placeholder="Cari Data {{ request()->query('type') === 'seminar' ? 'Seminar' : 'Sidang Akhir' }}" value="{{ $querySearch }}" autofocus />
	</div>
	<div class="middle">
		<table class="type-2">
			<thead>
				<tr>
					<th class="numbered">No</th>
					<th class="number">NIM</th>
					<th class="name">Nama</th>
					<th class="title">Judul</th>
					<th class="form">Form {{ $queryType === "seminar" ? "Pengumuman" : "Undangan" }}</th>
					<th class="form">Cetak {{ $queryType === "seminar" ? "Pengumuman" : "Undangan" }}</th>
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
						@if ($item->printable)
						<x-button class="form" id="edit-form-letter" icon="system-uicons:create" iconwidth="23" data-link="{{ $item->academicid }}">Ubah Form</x-button>
						@else
						<x-button class="form" id="add-form-letter" icon="system-uicons:create" iconwidth="23" data-link="{{ $item->academicid }}">Isi Form</x-button>
						@endif
					</td>
					<td class="form">
						@if ($item->printable)
						<form id="form-print" action="{{ route('admin.announcements.letter.print', [$item->academicid]) }}" method="POST">
							@csrf
							@method("POST")
							
							<x-button class="print" icon="material-symbols-light:print-outline-rounded" iconwidth="23">Cetak</x-button>
						</form>
						@else
						<x-button class="print" icon="material-symbols-light:print-outline-rounded" iconwidth="23" disabled>Cetak</x-button>
						@endif
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
			let $innerContent =
			`
				<x-input-wrapper id="letternumber" type="text" label="Nomor Surat" placeholder="Masukkan Nomor Surat" required />
				<x-input-wrapper id="moderator" type="select2" label="Moderator" placeholder="Pilih Dosen Moderator" :options="$dataLecturers" required />
				<x-input-wrapper id="letterdate" type="date" label="Tanggal Pembuatan" required />
			`;

			@if ($queryType === "thesisdefense")
				$innerContent +=
				`
					<x-input-wrapper id="supervisory_committee" type="select2" label="Ketua Komisi Pembimbing" placeholder="Pilih Ketua Komisi Pembimbing" :options="$dataLecturers" required />
					<x-input-wrapper id="external_examiner" type="select2" label="Penguji Luar Komisi" placeholder="Pilih Penguji Luar Komisi" :options="$dataLecturers" required />
					<x-input-wrapper id="chairman_session" type="select2" label="Ketua Sidang" placeholder="Pilih Ketua Sidang" :options="$dataLecturers" required />
				`;
			@endif

			return DialogInputData("{{ route('admin.announcements.letter.add', ':id') }}".replace(":id", $id) + "?type={{ $queryType }}", "Buat", "POST", $innerContent);
		});

		$(document).on("click", "button#edit-form-letter", function()
		{
			const $id = $(this).data("link");
			let $innerContent =
			`
				<x-input-wrapper id="letternumber" type="text" label="Nomor Surat" placeholder="Memuat Nomor Surat..." loading />
				<x-input-wrapper id="moderator" type="select2" label="Moderator" placeholder="Pilih Dosen Moderator" :options="$dataLecturers" required />
				<x-input-wrapper id="letterdate" type="text" label="Tanggal Pembuatan" placeholder="Memuat Tanggal Pembuatan..." loading />
			`;

			@if ($queryType === "thesisdefense")
				$innerContent +=
				`
					<x-input-wrapper id="supervisory_committee" type="select2" label="Ketua Komisi Pembimbing" placeholder="Pilih Ketua Komisi Pembimbing" :options="$dataLecturers" required />
					<x-input-wrapper id="external_examiner" type="select2" label="Penguji Luar Komisi" placeholder="Pilih Penguji Luar Komisi" :options="$dataLecturers" required />
					<x-input-wrapper id="chairman_session" type="select2" label="Ketua Sidang" placeholder="Pilih Ketua Sidang" :options="$dataLecturers" required />
				`;
			@endif

			DialogInputData("{{ route('admin.announcements.letter.update', ':id') }}".replace(":id", $id) + "?type={{ $queryType }}", "Ubah", "POST", $innerContent);

			$.get(`{{ route("admin.announcements.letter", ":id") }}`.replace(":id", $id), function(response)
			{
				const $letterNumber = response.letternumber ?? "";
				const $moderator = response.moderator ?? "";
				const $letterDate = response.letterdate ?? "";
				const $supervisoryCommittee = response.supervisory_committee ?? "";
				const $externalExaminer = response.external_examiner ?? "";
				const $chairmanSession = response.chairman_session ?? "";

				$("dialog.input-data .input-wrapper").has("#letternumber").replaceWith(`<x-input-wrapper id="letternumber" type="text" label="Nomor Surat" placeholder="Masukkan Nomor Surat" value="${$letterNumber}" required />`);
				$("dialog.input-data .input-wrapper").has("#letterdate").replaceWith(`<x-input-wrapper id="letterdate" type="date" label="Tanggal Pembuatan" value="${$letterDate}" required />`);
				
				$("dialog input#letternumber").focus();

				setTimeout(function()
				{
					const $selectModerator = $("dialog select#moderator");
					const $selectSupervisoryCommittee = $("dialog select#supervisory_committee");
					const $selectExternalExaminer = $("dialog select#external_examiner");
					const $selectChairmanSession = $("dialog select#chairman_session");

					if ($selectModerator.length && $selectModerator.find(`option[value="${$moderator}"]`).length)
						$selectModerator.val($moderator).trigger("change");
					
					if ($selectSupervisoryCommittee.length && $selectSupervisoryCommittee.find(`option[value="${$supervisoryCommittee}"]`).length)
						$selectSupervisoryCommittee.val($supervisoryCommittee).trigger("change");
					
					if ($selectExternalExaminer.length && $selectExternalExaminer.find(`option[value="${$externalExaminer}"]`).length)
						$selectExternalExaminer.val($externalExaminer).trigger("change");
					
					if ($selectChairmanSession.length && $selectChairmanSession.find(`option[value="${$chairmanSession}"]`).length)
						$selectChairmanSession.val($chairmanSession).trigger("change");

					ValidateForms();
				}, 100);
			});
		});
	</script>
</x-app-layout>