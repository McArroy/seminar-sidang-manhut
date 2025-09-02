@php
	$queryType = request()->query("type") ?? "";
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="{{ \App\Http\Controllers\HelperController::Asset('assets/css/pages/registrationform.css') }}">
	@endsection

	<x-slot name="title">Form Pendaftaran</x-slot>
	<x-slot name="icon">basil:document-outline</x-slot>

	@if ($queryType === "seminar")

	<x-slot name="pagetitle">Form Pendafataran Seminar Proposal</x-slot>

	@elseif ($queryType === "thesisdefense")

	@php $IsThesisDefense = true; @endphp

	<x-slot name="pagetitle">Form Pendafataran Sidang Akhir</x-slot>

	@endif

	<form action="{{ route('student.registrationform', ['type' => $queryType]) }}" method="POST">
		@csrf
		@method("POST")

		<div class="input">
			<x-input-wrapper id="username" label="Nama" type="text" value="{{ Auth::user()->username }}" readonly/>
			<x-input-wrapper id="useridnumber" label="NIM" type="text" value="{{ strtoupper(Auth::user()->useridnumber) }}" readonly/>
		</div>

		@if (isset($IsThesisDefense))
		<div class="input">
			<x-input-wrapper id="semester" label="Semester" type="text" placeholder="8 (Delapan)" required/>
		</div>
		<div class="input">
			<x-input-wrapper id="address" label="Alamat di Bogor" type="textarea" placeholder="Perumahan Taman Dramaga Permai Blok C1 No. 2" required/>
		</div>
		@else
		<div class="input">
			<x-input-wrapper id="studyprogram" label="Program Studi" type="text" placeholder="Masukkan Program Studi" value="Departemen Manajemen Hutan" required/>
			<x-input-wrapper id="department" label="Departemen" type="text" placeholder="Masukkan Departemen" value="Departemen Manajemen Hutan" required/>
		</div>
		@endif

		<div class="input">
			<x-input-wrapper id="supervisor1" label="Dosen Pembimbing 1" type="select2" placeholder="Pilih Dosen Pembimbing 1" :options="$dataLecturers" required />
		</div>
		<div class="input">
			<x-input-wrapper id="supervisor2" label="Dosen Pembimbing 2" type="select2" placeholder="Pilih Dosen Pembimbing 2" :options="$dataLecturers" />
		</div>
		<div class="input">
			<x-input-wrapper id="date" label="Tanggal {{ (isset($IsThesisDefense) ? 'Sidang' : 'Seminar') }}" type="date" required/>
			<x-input-wrapper id="time" label="Waktu" type="select2" placeholder="Pilih Waktu {{ (isset($IsThesisDefense) ? 'Sidang' : 'Seminar') }}" :options="$dataTime" required/>
		</div>
		<div class="input">
			<x-input-wrapper id="room" label="Tempat/Ruangan" type="select2" placeholder="Pilih Ruang Sidang" :options="$dataRooms->pluck('roomname', 'roomname')->toArray()" required />
		</div>
		<div class="input">
			<x-input-wrapper id="title" label="Judul Skripsi" type="textarea" placeholder="Masukkan Judul Skripsi" required/>
		</div>

		<x-button>SIMPAN</x-button>
	</form>
</x-app-layout>