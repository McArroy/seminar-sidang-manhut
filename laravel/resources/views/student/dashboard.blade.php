@php
use App\Http\Controllers\DateIndoFormatterController;
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
			@forelse ($dataSubmissions as $index => $item)
			<tr>
				<td class="numbered"></td>
				<td>{{ ucfirst($item->submission_type) }}</td>
				<td>{{ DateIndoFormatterController::Simple($item->created_at) }}</td>
				<td>{{ $item->comment }}</td>
				<td>
					@if (!empty($item->link))
					<x-button href="{{ $item->link }}" class="folder active" icon="fluent:folder-open-20-filled" iconwidth="30"></x-button>
					@else
					<x-button class="folder" icon="fluent:folder-open-20-filled" iconwidth="30" onclick="return DialogMessage(0, ['Dokumen Tidak Tersedia', 'Silakan Kirim Dokumen Berupa Link Google Drive Di Menu Persyaratan {{ ucfirst($item->submission_type) }}'], ['Kembali']);"></x-button>
					@endif
				</td>
				<td>
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
				<td>
					@php
					$baseParams =
					[
						"useridnumber" => $item->useridnumber,
						"supervisor1" => $item->supervisor1,
						"supervisor2" => $item->supervisor2,
						"date" => $item->date,
						"time" => $item->time,
						"place" => $item->place,
						"title" => $item->title
					];

					if ($item->submission_type === "Seminar")
					{
						$pageName = "seminar";
						$extraParams =
						[
							"type" => $pageName,
							"studyprogram" => $item->studyprogram,
							"department" => $item->department
						];
					}
					else
					{
						$pageName = "thesisdefense";
						$extraParams =
						[
							"type" => $pageName,
							"semester" => $item->semester,
							"address" => $item->address
						];
					}

					$query = http_build_query(array_merge($baseParams, $extraParams));

					$url = route("student.registrationletterrepreview") . "?" . $query;
					@endphp

					<x-button href="{!! $url !!}" class="viewform" icon="fe:document" iconwidth="25">Lihat</x-button>
				</td>
				<td>
					<form action="{{ route('student.' . $pageName . '.delete', $item->{$pageName . 'id'}) }}" method="POST" onsubmit="return FormConfirmation(event, ['Anda Yakin Akan Menghapus Data Ini?', 'Pastikan Data Yang Anda Pilih Benar'], ['Batal', 'Hapus']);">
						@csrf
						@method("DELETE")
						
						<x-button class="remove">Hapus</x-button>
					</form>
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="8" class="not-found">Tidak Ada Data Yang Ditemukan</td>
			</tr>
			@endforelse
		</tbody>
	</table>
</x-app-layout>