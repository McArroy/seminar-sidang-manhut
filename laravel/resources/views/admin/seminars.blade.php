@php
	$InputSearch = $_GET["search"] ?? "";
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="/assets/css/pages/datatables.css?v=1.0">
	@endsection

	<x-slot name="title">Seminar</x-slot>
	<x-slot name="icon">fluent:form-28-regular</x-slot>
	<x-slot name="pagetitle">Seminar</x-slot>
	
	<div class="top">
		<x-input-wrapper class="search type-2" id="search" type="text" placeholder="Cari Data Seminar" value="{{ $InputSearch }}" autofocus />
	</div>
	<div class="middle">
		<table class="type-2">
			<thead>
				<tr>
					<th class="numbered">No</th>
					<th class="number">NIM</th>
					<th class="name">Nama</th>
					<th class="title">Judul</th>
					<th class="link">Dokumen</th>
					<th class="status">Status</th>
					<th class="actions">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($dataSeminars as $index => $item)
				<tr>
					<td class="numbered"></td>
					<td class="number">{{ strtoupper($item->useridnumber) }}</td>
					<td class="name">{!! $item->username ?? "<i>Data Mahasiswa<br>Tidak Ditemukan</i>" !!}</td>
					<td class="title">{{ $item->title }}</td>
					<td class="link">
						@if (!empty($item->link))
						<x-button href="{{ $item->link }}" target="_blank" class="folder active" icon="fluent:folder-open-20-filled" iconwidth="30"></x-button>
						@else
						<x-button class="folder" id="folder-link-admin" icon="fluent:folder-open-20-filled" iconwidth="30" data-text="{{ ucfirst($item->submission_type) }}"></x-button>
						@endif
					</td>
					<td class="status">
						@if (!empty($item->comment))
						<span class="status revised">Revisi</span>
						@else
						<span class="status waiting">Menunggu Verifikasi</span>
						@endif
					</td>
					<td class="button-actions">
						<form id="form-verification" action="{{ route('admin.seminars.accept', $item->seminarid) }}" method="POST">
							@csrf
							@method("POST")
							
							<x-button class="verification">Verifikasi</x-button>
						</form>
						<x-button class="revision" id="revision-seminar" data-link="{{ $item->seminarid }}">Revisi</x-button>
						<form id="form-rejection" action="{{ route('admin.seminars.reject', $item->seminarid) }}" method="POST">
							@csrf
							@method("POST")
							
							<x-button class="reject">Tolak</x-button>
						</form>
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="7" class="not-found">Tidak Ada Data Yang Ditemukan</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>

	<x-navigator-buttons :data="$dataSeminars" />
	
	<script>
		$(document).on("click", "button#revision-seminar", function()
		{
			const $seminarId = $(this).data("link");
			
			DialogInputData("{{ route('admin.seminars.revision', ':id') }}".replace(":id", $seminarId), "basil:document-outline", "Revisi Dokumen", "POST",
			`<x-input-wrapper id="comment" type="textarea" label="Komentar" placeholder="Memuat Komentar..." loading />`);

			$.get(`{{ url('/admin/seminars/comment') }}/${$seminarId}`, function(response)
			{
				const $seminarComment = response.comment ?? "";

				$("dialog.input-data .input-wrapper").replaceWith(`<x-input-wrapper id="comment" type="textarea" label="Komentar" placeholder="Masukkan Saran Revisi Anda" value="${$seminarComment}" required />`);
			});
		});
	</script>
</x-app-layout>