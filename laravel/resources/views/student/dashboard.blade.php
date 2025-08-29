<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="{{ \App\Http\Controllers\HelperController::Asset('assets/css/pages/dashboard.css') }}">
	@endsection

	<x-slot name="title">Dashboard</x-slot>
	<x-slot name="icon">material-symbols:dashboard-outline</x-slot>
	<x-slot name="pagetitle">Dashboard Mahasiswa</x-slot>
	
	<div class="top">
		Status Pendaftaran
	</div>
	<div class="middle">
		<table>
			<thead>
				<tr>
					<th class="numbered">No</th>
					<th class="type">Jenis Pengajuan</th>
					<th class="date">Tanggal Pengajuan</th>
					<th class="comment">Komentar</th>
					<th class="link">Dokumen</th>
					<th class="status">Status</th>
					<th class="actions">Formulir</th>
					<th class="actions">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($dataSubmissions as $index => $item)
				<tr>
					<td class="numbered"></td>
					<td class="type">{{ ucfirst($item->submission_type) }}</td>
					<td class="date">{{ $item->created_at_parsed }}</td>
					<td class="comment">{{ $item->comment }}</td>
					<td class="link">
						@php
						if ($item->submission_type === "Seminar")
							$dataLink = route('student.requirements', ['type' => 'seminar']);
						else
							$dataLink = route('student.requirements', ['type' => 'thesisdefense']);
						@endphp

						@if (!empty($item->link))
						<x-button href="{{ $item->link }}" target="_blank" class="folder active" icon="fluent:folder-open-20-filled" iconwidth="30"></x-button>
						@else
						<x-button class="folder" id="folder-link" icon="fluent:folder-open-20-filled" iconwidth="30" data-link="{{ $dataLink }}" data-text="{{ ucfirst($item->submission_type) }}"></x-button>
						@endif
					</td>
					<td class="status">
						@if ($item->status === 0)
						<span class="status rejected">Ditolak</span>
						@elseif ($item->status === 1)
						<span class="status verified">Disetujui</span>
						@elseif (!empty($item->comment))
						<span class="status revised">Revisi</span>
						@else
						<span class="status waiting">Menunggu Verifikasi</span>
						@endif
					</td>
					<td class="actions">
						@php
						if ($item->submission_type === "Seminar")
						{
							$pageName = "seminar";
							$params =
							[
								"id" => $item->seminarid,
								"type" => $pageName
							];
						}
						else
						{
							$pageName = "thesisdefense";
							$params =
							[
								"id" => $item->thesisdefenseid,
								"type" => $pageName
							];
						}

						$query = http_build_query($params);

						$url = route("student.registrationletterrepreview") . "?" . $query;
						@endphp

						<x-button href="{!! $url !!}" class="viewform" icon="fe:document" iconwidth="23">Lihat</x-button>
					</td>
					<td class="actions">
						@if ($item->status === 1)
						<x-button class="remove" disabled>Hapus</x-button>
						@else
						<form id="form-delete" action="{{ route('student.' . $pageName . '.delete', $item->{$pageName . 'id'}) }}" method="POST">
							@csrf
							@method("DELETE")
							
							<x-button class="remove">Hapus</x-button>
						</form>
						@endif
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="8" class="not-found">Tidak Ada Data Yang Ditemukan</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</x-app-layout>