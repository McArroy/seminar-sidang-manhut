@php
	$querySearch = request()->query("search") ?? "";
	$queryType = request()->query("type") ?? "seminar";
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="{{ \App\Http\Controllers\HelperController::Asset('assets/css/pages/datatables.css') }}">
	@endsection

	<x-slot name="title">{{ __($queryType . ".text") }}</x-slot>
	<x-slot name="icon">
		@php

		if ($queryType === "seminar")
			$icon = "fluent:form-28-regular";
		else
			$icon = "streamline-flex:presentation";

		@endphp

		{{ $icon }}
	</x-slot>
	<x-slot name="pagetitle">{{ __($queryType . ".text") }}</x-slot>
	
	<div class="top">
		<x-input-wrapper class="search type-2" id="search" type="text" placeholder="{{ __($queryType . '.search') }}" value="{{ $querySearch }}" autofocus />
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
				@forelse ($academics as $index => $item)
				<tr>
					<td class="numbered"></td>
					<td class="number">{{ strtoupper($item->useridnumber) }}</td>
					<td class="name">{!! $item->username ?? "<i>Data Mahasiswa<br>Tidak Ditemukan</i>" !!}</td>
					<td class="title">{{ $item->title }}</td>
					<td class="link">
						@if (!empty($item->link))
						<x-button href="{{ $item->link }}" target="_blank" class="folder active" icon="fluent:folder-open-20-filled" iconwidth="30"></x-button>
						@else
						<x-button class="folder" id="folder-link-admin" icon="fluent:folder-open-20-filled" iconwidth="30" data-text-title="{{ __($item->academictype . '.documentnotfound') }}" data-text-content="{{ __($item->academictype . '.requirementsnotadded') }}"></x-button>
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
						@if (!empty($item->link))
						<form id="form-verification" action="{{ route('admin.academics.accept', $item->academicid) . '?type=' . $item->academictype }}" method="POST">
							@csrf
							@method("POST")
							
							<x-button class="verification">Verifikasi</x-button>
						</form>
						@else
						<x-button class="verification" disabled>Verifikasi</x-button>
						@endif
						<x-button class="revision" id="revision-academic" data-link="{{ $item->academicid }}">Revisi</x-button>
						<form id="form-rejection" action="{{ route('admin.academics.reject', $item->academicid) . '?type=' . $item->academictype }}" method="POST">
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

	<x-navigator-buttons :data="$academics" />
	
	<script>
		$(document).on("click", "button#revision-academic", function()
		{
			const $academicid = $(this).data("link");
			
			DialogInputData("{{ route('admin.academics.revision', ':id') . '?type=' . $queryType }}".replace(":id", $academicid), "{{ $icon }}", "{{ __($queryType . '.revision') }}", "POST",
			`<x-input-wrapper id="comment" type="textarea" label="Komentar" placeholder="Memuat Komentar..." loading />`);

			$.get(`{{ route("admin.academics.comment", ":id") }}`.replace(":id", $academicid), function(response)
			{
				const $academicComment = response.comment ?? "";

				$("dialog.input-data .input-wrapper").replaceWith(`<x-input-wrapper id="comment" type="textarea" label="Komentar" placeholder="Masukkan Saran Revisi Anda" value="${$academicComment}" required />`);
				$("dialog textarea").focus();
				ValidateForms();
			});
		});
	</script>
</x-app-layout>