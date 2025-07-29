@php
use App\Http\Controllers\DateIndoFormatter;
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="/assets/css/pages/dashboard.css?v=1.0">
	@endsection

	<x-slot name="title">Dashboard</x-slot>
	<x-slot name="icon">material-symbols:dashboard-outline</x-slot>
	<x-slot name="pagetitle">Dashboard Mahasiswa</x-slot>
	
	<div class="top">
		Status Pendaftaran
	</div>
	<table>
		<thead>
			<tr>
				<th class="numbered">No</th>
				<th>Jenis Pengajuan</th>
				<th>Tanggal Pengajuan</th>
				<th>Komentar</th>
				<th>Dokumen</th>
				<th>Status</th>
				<th>Formulir</th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody>
			@forelse ($data as $index => $seminar)
			<tr>
				<td class="numbered"></td>
				<td>Seminar</td>
				<td>{{ DateIndoFormatter::Simple($seminar->created_at) }}</td>
				<td>{{ $seminar->comment }}</td>
				<td>
					@if (!empty($seminar->link))
					<x-button href="{{ $seminar->link }}" class="folder active" icon="fluent:folder-open-20-filled" iconwidth="30"></x-button>
					@else
					<x-button class="folder" icon="fluent:folder-open-20-filled" iconwidth="30" onclick="return DialogMessage(0, ['Dokumen Tidak Tersedia', 'Silakan Kirim Dokumen Berupa Link Google Drive Di Menu Persyaratan Seminar'], ['Kembali']);"></x-button>
					@endif
				</td>
				<td>
					@if ($seminar->status === 0)
					<span class="status rejected">Ditolak</span>
					@elseif ($seminar->status === 1)
					<span class="status verified">Disetujui</span>
					@elseif (!empty($seminar->comment))
					<span class="status revised">Revisi</span>
					@else
					<span class="status waiting">Menunggu Verifikasi</span>
					@endif
				</td>
				<td>
					@php
					$query = http_build_query(array_merge(["type" => "seminar"],
					[
						"useridnumber" => $seminar->useridnumber,
						"studyprogram" => $seminar->studyprogram,
						"department" => $seminar->department,
						"supervisor1" => $seminar->supervisor1,
						"supervisor2" => $seminar->supervisor2,
						"date" => $seminar->date,
						"time" => $seminar->time,
						"place" => $seminar->place,
						"title" => $seminar->title
					]));
					$url = route("student.registrationletterrepreview") . "?" . $query;
					@endphp

					<x-button href="{!! $url !!}" class="viewform" icon="fe:document" iconwidth="25">Lihat</x-button>
				</td>
				<td>
					<form action="{{ route('student.seminar.delete', $seminar->seminarid) }}" method="POST" onsubmit="return FormConfirmation(event, ['Anda Yakin Akan Menghapus Data Ini?', 'Pastikan Data Yang Anda Pilih Benar'], ['Batal', 'Hapus']);">
						@csrf
						@method("DELETE")
						
						<x-button class="remove">Hapus</x-button>
					</form>
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="8" class="text-center">Tidak Ada Data Seminar</td>
			</tr>
			@endforelse
		</tbody>
	</table>
</x-app-layout>