@php
	if (!in_array($_GET["type"] ?? null, ["seminar", "thesisdefense"]))
	{
		header("Location: " . url()->current() . "?type=seminar");
		exit;
	}
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="/assets/css/pages/registrationform.css?v=1.0">
	@endsection

	@section("activate-navbar", "active")

	<x-slot name="title">Form Pendaftaran</x-slot>
	<x-slot name="icon">basil:document-outline</x-slot>

	@if ($_GET["type"] === "seminar")

	<x-slot name="pagetitle">Form Pendafataran Seminar Proposal</x-slot>

	@elseif ($_GET["type"] === "thesisdefense")

	@php $IsThesisDefense = true; @endphp

	<x-slot name="pagetitle">Form Pendafataran Sidang Akhir</x-slot>

	@endif

	<form action="{{ route('student.registrationform', ['type' => $_GET['type']]) }}" method="POST">
		@csrf

		<div class="input">
			<x-input-wrapper id="username" label="Nama" type="text" value="{{ Auth::user()->username }}" readonly/>
			<x-input-wrapper id="useridnumber" label="NIM" type="text" value="{{ Auth::user()->useridnumber }}" readonly/>
		</div>

		@if (isset($IsThesisDefense))
		<div class="input">
			<x-input-wrapper id="semester" label="Semester" type="text" placeholder="8 (Delapan)" required/>
		</div>
		<div class="input">
			<x-input-wrapper id="address" label="Alamat di Bogor" type="textarea" placeholder="Perumahan Taman Dramaga  Permai Blok C1 No. 2" required/>
		</div>
		@else
		<div class="input">
			<x-input-wrapper id="studyprogram" label="Program Studi" type="text" placeholder="Masukkan Program Studi" required/>
			<x-input-wrapper id="department" label="Departemen" type="text" placeholder="Masukkan Departemen" required/>
		</div>
		@endif

		<div class="input">
			<x-input-wrapper id="supervisor1" label="Dosen Pembimbing 1" type="select" placeholder="Pilih Dosen Pembimbing 1" :options="
			[
				'Dosen 1 - 01X1234567890' => 'Dosen Pembimbing 1',
				'Dosen 2 - 02X1234567890' => 'Dosen Pembimbing 2',
				'Dosen 3 - 03X1234567890' => 'Dosen Pembimbing 3'
			]" required/>
		</div>
		<div class="input">
			<x-input-wrapper id="supervisor2" label="Dosen Pembimbing 2" type="select" placeholder="Pilih Dosen Pembimbing 2" :options="
			[
				'Dosen 1 - 11X1234567890' => 'Dosen Pembimbing 1',
				'Dosen 2 - 12X1234567890' => 'Dosen Pembimbing 2',
				'Dosen 3 - 13X1234567890' => 'Dosen Pembimbing 3'
			]" required/>
		</div>
		<div class="input">
			<x-input-wrapper id="date" label="Tanggal {{ (isset($IsThesisDefense) ? 'Sidang' : 'Seminar') }}" type="date" required/>
			<x-input-wrapper id="time" label="Waktu" type="select" placeholder="Pilih Waktu {{ (isset($IsThesisDefense) ? 'Sidang' : 'Seminar') }}" :options="
			(isset($IsThesisDefense) && $IsThesisDefense
				?
			[
				'07:00 - 09:00' => '07:00 - 09:00',
				'07:30 - 09:30' => '07:30 - 09:30',
				'08:00 - 10:00' => '08:00 - 10:00',
				'08:30 - 10:30' => '08:30 - 10:30',
				'09:00 - 11:00' => '09:00 - 11:00',
				'09:30 - 11:30' => '09:30 - 11:30',
				'10:00 - 12:00' => '10:00 - 12:00',
				'10:30 - 12:30' => '10:30 - 12:30',
				'11:00 - 13:00' => '11:00 - 13:00',
				'11:30 - 13:30' => '11:30 - 13:30',
				'12:00 - 14:00' => '12:00 - 14:00',
				'12:30 - 14:30' => '12:30 - 14:30',
				'13:00 - 15:00' => '13:00 - 15:00',
				'13:30 - 15:30' => '13:30 - 15:30',
				'14:00 - 16:00' => '14:00 - 16:00',
				'14:30 - 16:30' => '14:30 - 16:30',
				'15:00 - 17:00' => '15:00 - 17:00',
				'15:30 - 17:30' => '15:30 - 17:30',
				'16:00 - 18:00' => '16:00 - 18:00'
			]
				:
			[
				'07:00 - 08:00' => '07:00 - 08:00',
				'07:30 - 08:30' => '07:30 - 08:30',
				'08:00 - 09:00' => '08:00 - 09:00',
				'08:30 - 09:30' => '08:30 - 09:30',
				'09:00 - 10:00' => '09:00 - 10:00',
				'09:30 - 10:30' => '09:30 - 10:30',
				'10:00 - 11:00' => '10:00 - 11:00',
				'10:30 - 11:30' => '10:30 - 11:30',
				'11:00 - 12:00' => '11:00 - 12:00',
				'11:30 - 12:30' => '11:30 - 12:30',
				'12:00 - 13:00' => '12:00 - 13:00',
				'12:30 - 13:30' => '12:30 - 13:30',
				'13:00 - 14:00' => '13:00 - 14:00',
				'13:30 - 14:30' => '13:30 - 14:30',
				'14:00 - 15:00' => '14:00 - 15:00',
				'14:30 - 15:30' => '14:30 - 15:30',
				'15:00 - 16:00' => '15:00 - 16:00',
				'15:30 - 16:30' => '15:30 - 16:30',
				'16:00 - 17:00' => '16:00 - 17:00'
			])" required/>
		</div>
		<div class="input">
			<x-input-wrapper id="place" label="Tempat/Ruangan" type="select" placeholder="Pilih Ruang Sidang {{ (isset($IsThesisDefense) ? 'Foresta' : 'Matoa') }}" :options="
			[
				'Ruang 1' => 'Ruang Sidang 1',
				'Ruang 2' => 'Ruang Sidang 2',
				'Ruang 3' => 'Ruang Sidang 3'
			]" required/>
		</div>
		<div class="input">
			<x-input-wrapper id="title" label="Judul Skripsi" type="textarea" placeholder="Masukkan Judul Skripsi" required/>
		</div>

		<x-button>SIMPAN</x-button>
	</form>
</x-app-layout>