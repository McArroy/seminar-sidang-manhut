<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="/assets/css/pages/registrationform.css?v=1.0">
		<link rel="stylesheet" href="/assets/css/pages/requirements.css?v=1.0">
	@endsection

	@section("activate-navbar", "active")

	@php
		if (!in_array($_GET["type"] ?? null, ["seminar", "thesisdefense"]))
		{
			header("Location: " . url()->current() . "?type=seminar");
			exit;
		}
	@endphp

	<x-slot name="icon">hugeicons:folder-upload</x-slot>

	@if ($_GET["type"] === "seminar")

	@php $IsThesisDefense = false; @endphp

	<x-slot name="title">Persyaratan Seminar</x-slot>
	<x-slot name="pagetitle">Persyaratan Seminar</x-slot>

	@elseif ($_GET["type"] === "thesisdefense")

	@php $IsThesisDefense = true; @endphp

	<x-slot name="title">Persyaratan Sidang</x-slot>
	<x-slot name="pagetitle">Persyaratan Sidang</x-slot>

	@endif

	<div class="letter">
		<p>Upload persyaratan seminar proposal pada Google Drive pribadi dan buat hak akses folder menjadi public (agar bisa diakses).</p>
		@if ($IsThesisDefense)
		<p>Persyaratan sidang akhir berupa:</p>
		<p><span>1. FORMULIR PENDAFTARAN SIDANG AKHIR SARJANA</span> yang sudah ditandatangani Komisi Pembimbing</p>
		<p><span>2. Tanda bukti lunas SPP</span> semester akhir</p>
		<p><span>3. ABSTRAK RINGKASAN SKRIPSI VERSI INGGRIS DAN INDONESIA</span> yang telah ditandantangani Komisi Pembimbing</p>
		<p><span>4. BUKU KONSULTASI</span> yang sudah diisi dengan lengkap</p>
		<p><span>5. DRAFT SKRIPSI</span> yang telah memenuhi syarat ujian akhir dan telah ditandatangani oleh Dosen Komisi Pembimbing</p>
		@else
		<p>Persyaratan seminar proposal berupa:</p>
		<p><span>1. FORMULIR PENDAFTARAN SEMINAR</span> yang sudah ditandatangani Dosen Pembimbing dan Komisi AJMP dan Kemahasiswaan</p>
		<p><span>2. Softfile Ringkasan Seminar</span></p>
		<p><span>3. Tanda bukti penyerahan Proposal Seminar</span> dari Departemen Manajemen Hutan</p>
		<p><span>4. Tanda bukti lunas SPP</span> semester akhir</p>
		<p><span>5. Tanda bukti telah menyelesaikan MINIMAL 137 SKS</span> dari mata kuliah selain kegiatan Praktek Umum dengan <span>IPK >= 2.00</span> (dari AJMP)</p>
		<p><span>6. Tanda bukti telah mengikuti Seminar Praktek Khusus minimal 15 kali</span> dengan komposisi:</p>
		<p class="indented">a. 10 kali seminar dari Departemen MNH</p>
		<p class="indented">b. 5 kali seminar diluar Departemen MNH (DHH, DKSHE, DSVK)</p>
		<p><span>7. Tanda bukti telah mengikuti pelaksanaan Praktek Lapang</span></p>
		@endif
	</div>

	<form class="link letter" action="{{ route('student.requirements') . '?type=' . $_GET['type'] }}" method="POST" onsubmit="if (IsGoogleDriveUrl($('#link').val().trim())) { return FormConfirmation(event, ['Anda Yakin Akan Menyimpan Data Ini?', 'Pastikan Data Yang Dimasukkan Benar'], ['Batal', 'Simpan']); } { DialogMessage(0, ['Terjadi Kesalahan!', 'Pastikan Link Yang Anda Masukkan Adalah Link GoogleDrive'], ['Kembali']); return event.preventDefault(); }">
		@csrf

		<p>Masukan Link Google Drive pada inputan di bawah dengan format yang sesuai.</p>
		<p>Contoh format: <span>https://drive.google.com/drive/folders/link_sharing</span></p>
		<div class="input">
			<x-input-wrapper id="link" type="url" placeholder="Masukkan link di sini" required/>
		</div>

		<x-button>SUBMIT</x-button>
	</form>
</x-app-layout>