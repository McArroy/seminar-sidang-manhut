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
			<tr>
				<td class="numbered"></td>
				<td>Seminar</td>
				<td>21 Juni 2010</td>
				<td></td>
				<td><x-button class="folder" icon="fluent:folder-open-20-filled" iconwidth="30"></x-button></td>
				<td><span class="status waiting">Menunggu Verifikasi</span></td>
				<td><x-button class="viewform" icon="fe:document" iconwidth="25">Lihat</x-button></td>
				<td><x-button class="remove" onclick="return DialogMessage(1, ['Anda Yakin Akan Menghapus Data Ini?', 'Pastikan Data Yang Anda Pilih Benar'], ['Batal', 'Hapus']);">Hapus</x-button></td>
			</tr>
			<tr>
				<td class="numbered"></td>
				<td>Sidang</td>
				<td>21 Juni 2010</td>
				<td>Lengkapi Keterangan Lunas UKT</td>
				<td><x-button class="folder" icon="fluent:folder-open-20-filled" iconwidth="30"></x-button></td>
				<td><span class="status revised">Revisi</span></td>
				<td><x-button class="viewform" icon="fe:document" iconwidth="25">Lihat</x-button></td>
				<td><x-button class="remove" onclick="return DialogMessage(1, ['Anda Yakin Akan Menghapus Data Ini?', 'Pastikan Data Yang Anda Pilih Benar'], ['Batal', 'Hapus']);">Hapus</x-button></td>
			</tr>
			<tr>
				<td class="numbered"></td>
				<td>Seminar</td>
				<td>21 Juni 2010</td>
				<td>File Transkrip Nilai Tidak Bisa Dibuka</td>
				<td><x-button class="folder" icon="fluent:folder-open-20-filled" iconwidth="30"></x-button></td>
				<td><span class="status revised">Revisi</span></td>
				<td><x-button class="viewform" icon="fe:document" iconwidth="25">Lihat</x-button></td>
				<td><x-button class="remove" onclick="return DialogMessage(1, ['Anda Yakin Akan Menghapus Data Ini?', 'Pastikan Data Yang Anda Pilih Benar'], ['Batal', 'Hapus']);">Hapus</x-button></td>
			</tr>
			<tr>
				<td class="numbered"></td>
				<td>Sidang</td>
				<td>21 Juni 2010</td>
				<td></td>
				<td><x-button class="folder" icon="fluent:folder-open-20-filled" iconwidth="30"></x-button></td>
				<td><span class="status waiting">Menunggu Verifikasi</span></td>
				<td><x-button class="viewform" icon="fe:document" iconwidth="25">Lihat</x-button></td>
				<td><x-button class="remove" onclick="return DialogMessage(1, ['Anda Yakin Akan Menghapus Data Ini?', 'Pastikan Data Yang Anda Pilih Benar'], ['Batal', 'Hapus']);">Hapus</x-button></td>
			</tr>
		</tbody>
	</table>
</x-app-layout>