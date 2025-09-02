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
				@forelse ($academics as $index => $item)
				<tr>
					<td class="numbered"></td>
					<td class="type">{{ __($item->academictype . ".text") }}</td>
					<td class="date">{{ $item->created_at_parsed }}</td>
					<td class="comment">{{ $item->comment }}</td>
					<td class="link">
						@php
						if ($item->academictype === "seminar")
							$dataLink = route('student.requirements', ['type' => 'seminar']);
						else
							$dataLink = route('student.requirements', ['type' => 'thesisdefense']);
						@endphp

						@if (!empty($item->link))
						<x-button href="{{ $item->link }}" target="_blank" class="folder active" icon="fluent:folder-open-20-filled" iconwidth="30"></x-button>
						@else
						<x-button class="folder" id="folder-link" icon="fluent:folder-open-20-filled" iconwidth="30" data-link="{{ $dataLink }}" data-text="{{ __($item->academictype . '.regulation') }}"></x-button>
						@endif
					</td>
					<td class="status">
						@if ($item->is_accepted === 0)
						<span class="status rejected">Ditolak</span>
						@elseif ($item->is_accepted === 1)
						<span class="status verified">Disetujui</span>
						@elseif (!empty($item->comment))
						<span class="status revised">Revisi</span>
						@else
						<span class="status waiting">Menunggu Verifikasi</span>
						@endif
					</td>
					<td class="actions">
						<x-button href="{{ route('student.registrationletterrepreview', $item->academicid) . '?type=' . $item->academictype }}" class="viewform" icon="fe:document" iconwidth="23">Lihat</x-button>
					</td>
					<td class="actions">
						@if ($item->is_accepted === 1)
						<x-button class="remove" disabled>Hapus</x-button>
						@else
						<form id="form-delete" action="{{ route('student.academic.delete', $item->academicid) . '?type=' . $item->academictype }}" method="POST">
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