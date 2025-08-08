<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SeminarController;
use App\Http\Controllers\ThesisdefenseController;

date_default_timezone_set("Asia/Jakarta");

$HashFile = "Uvuvwevwevwe Onyetenyevwe Ugwemuhwem Osas";

?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>{{ isset($title) ? config("app.name") . " | " . $title : config("app.name") }}</title>

		<link rel="stylesheet" href="/assets/css/elements/animations.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/elements/colors.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/elements/indexes.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/elements/transitions.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/elements/commons.css?hash=<?= $HashFile ?>">

		<link rel="stylesheet" href="/assets/css/elements/texts.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/elements/buttons.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/elements/contents.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/elements/dialogs.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/elements/footer.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/elements/inputs.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/elements/letter-viewer.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/elements/loading-animations.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/elements/navigationbar.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/elements/navigator-buttons.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/elements/scrollbar.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/elements/tables.css?hash=<?= $HashFile ?>">

		@yield("css")

		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
		<script src="https://cdn.jsdelivr.net/npm/jquery@latest/dist/jquery.min.js"></script>
		<script src="https://code.iconify.design/iconify-icon/3.0.0/iconify-icon.min.js"></script>
		<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/html2pdf.js@latest/dist/html2pdf.bundle.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/chart.umd.min.js"></script>
		
		<script src="/assets/js/functions.js?hash=<?= $HashFile ?>"></script>
		<script src="/assets/js/dialog.js?hash=<?= $HashFile ?>"></script>
		<script src="/assets/js/form.js?hash=<?= $HashFile ?>"></script>
		<script src="/assets/js/pdf.js?hash=<?= $HashFile ?>"></script>
		<script src="/assets/js/commons.js?hash=<?= $HashFile ?>"></script>
	</head>
	<body>
		<loading class="active">
			<div class="container">
				<div class="morphing-shape"></div>
				<div class="loading-text">{{ config("app.name") }}</div>
				<p>Mohon Tunggu Sebentar ...</p>
			</div>
		</loading>

		@if (request()->has("addstudent") || request()->has("editstudent") || request()->has("addlecturer") || request()->has("editlecturer") || request()->has("seminarcomment") || request()->has("thesisdefensecomment") || request()->has("announcementform"))
		@php
			if (request()->has("addstudent") || request()->has("editstudent"))
			{
				$path = route("admin.students.add");
				$icon = "heroicons:user-group-solid";
				$title = "Tambah Data Mahasiswa";
				$type = [0, "post"];
				$elements = ["NIM", "NIM Mahasiswa", "Nama", "Nama Mahasiswa", "Kata Sandi"];

				if (request()->has("editstudent"))
				{
					$path = route("admin.students.update", [request("editstudent")]);
					$title = "Ubah Data Mahasiswa";
					$type = [0, "edit"];
					$values =
					[
						strtoupper(UserController::GetUseridnumberById(request('editstudent'))),
						UserController::GetUsernameById(request('editstudent'))
					];
				}
			}
			else if (request()->has("addlecturer") || request()->has("editlecturer"))
			{
				$path = route("admin.lecturers.add");
				$icon = "heroicons:user-group-solid";
				$title = "Tambah Data Dosen";
				$type = [1, "post"];
				$elements = ["NIP", "NIP Dosen", "Nama", "Nama Dosen", "Kata Sandi"];

				if (request()->has("editlecturer"))
				{
					$path = route("admin.lecturers.update", [request("editlecturer")]);
					$title = "Ubah Data Dosen";
					$type = [1, "edit"];
					$values =
					[
						strtoupper(UserController::GetUseridnumberById(request('editlecturer'))),
						UserController::GetUsernameById(request('editlecturer'))
					];
				}
			}
			else if (request()->has("seminarcomment") || request()->has("thesisdefensecomment"))
			{
				$icon = "basil:document-outline";
				$title = "Revisi Dokumen";
				$type = [2, "post"];
				$elements = ["Komentar", "Masukkan Saran Revisi Anda"];

				if (request()->has("seminarcomment"))
				{
					$path = route("admin.seminars.revision", [request("seminarcomment")]);
					$values =
					[
						SeminarController::GetCommentById(request('seminarcomment'))
					];
				}
				else if (request()->has("thesisdefensecomment"))
				{
					$path = route("admin.thesisdefenses.revision", [request("thesisdefensecomment")]);
					$values =
					[
						ThesisdefenseController::GetCommentById(request('thesisdefensecomment'))
					];
				}
			}
			else if (request()->has("announcementform"))
			{
				$icon = "heroicons:user-group-solid";
				$elements = ["Nomor Surat", "Moderator", "Kata Sandi"];

				if (request()->query("type") === "seminar")
				{
					$path = route("admin.announcements.seminar.add", [request("announcementform")]);
					$type = [3, "post"];
					$title = "Pengumuman Seminar";
				}
				else if (request()->query("type") === "thesisdefense")
				{
					$path = route("admin.announcements.thesisdefense.add", [request("announcementform")]);
					$type = [4, "post"];
					$title = "Undangan Sidang Akhir";
				}
			}
		@endphp

		<dialog class="input-data">
			<form action="{{ $path }}" method="POST">
				@csrf
				@method("POST")

				<div class="top">
					<img src="/assets/img/background-banner.png" alt="background-banner">
					<div class="text">
						<iconify-icon icon="{{ $icon }}" width="24"></iconify-icon>
						{{ $title }}
					</div>
				</div>
				<div class="content">
					@if ($type[0] === 0 || $type[0] === 1)
					<x-input-wrapper id="useridnumber" type="text" label="{{ $elements[0] }}" placeholder="Masukkan {{ $elements[1] }}" value="{{ $values[0] ?? '' }}" required />
					<x-input-wrapper id="username" type="text" label="{{ $elements[2] }}" placeholder="Masukkan {{ $elements[3] }}" value="{{ $values[1] ?? '' }}" required />
					<x-input-wrapper class="password" id="password" type="password" label="{{ $elements[4] }}" placeholder="********" required="{{ $type[1] !== 'edit' }}">
						<iconify-icon icon="basil:eye-outline" class="show-hide-password" width="24" onclick="TogglePassword($(this))"></iconify-icon>
					</x-input-wrapper>
					@elseif ($type[0] === 2)
					<x-input-wrapper id="comment" type="textarea" label="{{ $elements[0] }}" placeholder="Masukkan {{ $elements[1] }}" value="{{ $values[0] ?? '' }}" required />
					@elseif ($type[0] === 3 || $type[0] === 4)
					@php
						$dataLecturers = app()->make(UserController::class)->GetLecturers();
					@endphp
					<x-input-wrapper id="number_letter" type="text" label="Nomor Surat" placeholder="Masukkan Nomor Surat" required />
					<x-input-wrapper id="moderator" label="Moderator" type="select" placeholder="Pilih Dosen Moderator" required>
						@foreach ($dataLecturers as $lecturer)
							<option value="{{ strtoupper($lecturer->useridnumber) }}">{{ $lecturer->username }}</option>
						@endforeach
					</x-input-wrapper>
					<x-input-wrapper id="date_letter" type="date" label="Tanggal Pembuatan" required />
					@endif

					@if ($type[0] === 4)
					<x-input-wrapper id="supervisory_committee" label="Ketua Komisi Pembimbing" type="select" placeholder="Pilih Ketua Komisi Pembimbing" required>
						@foreach ($dataLecturers as $lecturer)
							<option value="{{ strtoupper($lecturer->useridnumber) }}">{{ $lecturer->username }}</option>
						@endforeach
					</x-input-wrapper>
					<x-input-wrapper id="external_examiner" label="Penguji Luar Komisi" type="select" placeholder="Pilih Penguji Luar Komisi" required>
						@foreach ($dataLecturers as $lecturer)
							<option value="{{ strtoupper($lecturer->useridnumber) }}">{{ $lecturer->username }}</option>
						@endforeach
					</x-input-wrapper>
					<x-input-wrapper id="chairman_session" label="Ketua Sidang" type="select" placeholder="Pilih Ketua Sidang" required>
						@foreach ($dataLecturers as $lecturer)
							<option value="{{ strtoupper($lecturer->useridnumber) }}">{{ $lecturer->username }}</option>
						@endforeach
					</x-input-wrapper>
					@endif
					<div class="buttons">
						<x-button class="confirmation-close">Batal</x-button>
						<x-button class="confirmation-ok active">Simpan</x-button>
					</div>
				</div>
			</form>
		</dialog>

		<script>
			$("dialog.input-data")[0].showModal();
		</script>
		@endif

		@auth
		<navbar class="top">
			<div class="left">
				<x-ipb-logo/>
				<div class="text">
					<h1>Sistem Pendaftaran Seminar</h1>
					<h1>Departemen Manajemen Hutan</h1>
				</div>
				<iconify-icon icon="material-symbols:menu-rounded" width="30" onclick="ToggleNavbar();"></iconify-icon>
			</div>
			<div class="right">
				<div class="text">
					<h1>Halo,&nbsp;</h1>
					<h1>{{ Auth::user()->username }}</h1>
				</div>
			</div>
		</navbar>

		<div class="footer">
			<h6>© <?= getdate()["year"] ?> Forest Management — All Rights Reserved</h6>
		</div>
		@endauth

		<div class="content-container">
			@auth
			<navbar class="side @yield('activate-navbar')">
				<x-nav-link href="{{ route(Auth::user()->userrole . '.dashboard') }}" :active="request()->routeIs(Auth::user()->userrole . '.dashboard')">
					<iconify-icon icon="material-symbols:dashboard-outline" width="21"></iconify-icon>
					Dashboard
				</x-nav-link>

				<h1>Menu Utama</h1>

				@if (Auth::user()->userrole === "admin")

				<x-nav-link href="{{ route('admin.students') }}" :active="request()->routeIs('admin.students')">
					<iconify-icon icon="heroicons:user-group-solid" width="21"></iconify-icon>
					Data Mahasiswa
				</x-nav-link>
				<x-nav-link href="{{ route('admin.lecturers') }}" :active="request()->routeIs('admin.lecturers')">
					<iconify-icon icon="fontisto:person" width="15"></iconify-icon>
					Data Dosen
				</x-nav-link>
				<x-nav-link href="{{ route('admin.seminars') }}" :active="request()->routeIs('admin.seminars')">
					<iconify-icon icon="fluent:form-28-regular" width="21"></iconify-icon>
					Seminar
				</x-nav-link>
				<x-nav-link href="{{ route('admin.announcements', ['type' => 'seminar']) }}" :active="request()->routeIs('admin.announcements') && request()->query('type') === 'seminar'">
					<iconify-icon icon="hugeicons:folder-upload" width="21"></iconify-icon>
					Pengumuman Seminar
				</x-nav-link>
				<x-nav-link href="{{ route('admin.thesisdefenses') }}" :active="request()->routeIs('admin.thesisdefenses')">
					<iconify-icon icon="hugeicons:folder-upload" width="21"></iconify-icon>
					Sidang Akhir
				</x-nav-link>
				<x-nav-link href="{{ route('admin.announcements', ['type' => 'thesisdefense']) }}" :active="request()->routeIs('admin.announcements') && request()->query('type') === 'thesisdefense'">
					<iconify-icon icon="hugeicons:folder-upload" width="21"></iconify-icon>
					Undangan Sidang
				</x-nav-link>

				@elseif (Auth::user()->userrole === "student")

				<x-nav-link href="{{ route('student.flow', ['type' => 'seminar']) }}" class="button-list" :active="request()->routeIs('student.flow') && request()->query('type') === 'seminar' || request()->routeIs('student.registrationform') && request()->query('type') === 'seminar' || request()->routeIs('student.registrationletter') && request()->query('type') === 'seminar' || request()->routeIs('student.requirements') && request()->query('type') === 'seminar'" onclick="ToggleButtonList($(this))">
					<iconify-icon icon="fluent:form-28-regular" width="21"></iconify-icon>
					Daftar Seminar
					<iconify-icon icon="weui:arrow-filled" width="12"></iconify-icon>
				</x-nav-link>
				<x-nav-link-dropdown :active="request()->routeIs('student.flow') && request()->query('type') === 'seminar' || request()->routeIs('student.registrationform') && request()->query('type') === 'seminar' || request()->routeIs('student.registrationletter') && request()->query('type') === 'seminar' || request()->routeIs('student.requirements') && request()->query('type') === 'seminar'">
					<x-nav-link href="{{ route('student.registrationform', ['type' => 'seminar']) }}" :active="request()->routeIs('student.registrationform') && request()->query('type') === 'seminar' || request()->routeIs('student.registrationletter') && request()->query('type') === 'seminar'">
						<iconify-icon icon="basil:document-outline" width="21"></iconify-icon>
						Form Pendaftaran
					</x-nav-link>
					<x-nav-link href="{{ route('student.requirements', ['type' => 'seminar']) }}" :active="request()->routeIs('student.requirements') && request()->query('type') === 'seminar'">
						<iconify-icon icon="hugeicons:folder-upload" width="21"></iconify-icon>
						Persyaratan Seminar
					</x-nav-link>
				</x-nav-link-dropdown>

				<x-nav-link href="{{ route('student.flow', ['type' => 'thesisdefense']) }}" class="button-list" :active="request()->routeIs('student.flow') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.registrationform') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.registrationletter') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.requirements') && request()->query('type') === 'thesisdefense'" onclick="ToggleButtonList($(this))">
					<iconify-icon icon="streamline-flex:presentation" width="21"></iconify-icon>
					Daftar Sidang Akhir
					<iconify-icon icon="weui:arrow-filled" width="12"></iconify-icon>
				</x-nav-link>
				<x-nav-link-dropdown :active="request()->routeIs('student.flow') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.registrationform') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.registrationletter') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.requirements') && request()->query('type') === 'thesisdefense'">
					<x-nav-link href="{{ route('student.registrationform', ['type' => 'thesisdefense']) }}" :active="request()->routeIs('student.registrationform') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.registrationletter') && request()->query('type') === 'thesisdefense'">
						<iconify-icon icon="basil:document-outline" width="21"></iconify-icon>
						Form Pendaftaran
					</x-nav-link>
					<x-nav-link href="{{ route('student.requirements', ['type' => 'thesisdefense']) }}" :active="request()->routeIs('student.requirements') && request()->query('type') === 'thesisdefense'">
						<iconify-icon icon="hugeicons:folder-upload" width="21"></iconify-icon>
						Persyaratan Sidang
					</x-nav-link>
				</x-nav-link-dropdown>
				
				@endif

				<x-nav-link href="{{ route(Auth::user()->userrole . '.schedule') }}" :active="request()->routeIs(Auth::user()->userrole . '.schedule')">
					<iconify-icon icon="material-symbols-light:calendar-clock-outline-sharp" width="21"></iconify-icon>
					Jadwal
				</x-nav-link>

				<form id="form-logout" action="{{ route('logout') }}" method="POST">
					@csrf

					<button href="javascript:void(0);" class="button button-logout">
						<iconify-icon icon="material-symbols:logout-rounded" width="21"></iconify-icon>
						Keluar
					</button>
				</form>
			</navbar>

			<div class="main-content">
				<img src="/assets/img/background-banner.png?v=1.0" alt="background-banner" class="background-banner">
				<div class="wrapper">
					<h1>
						<iconify-icon icon="{{ $icon }}" width="24"></iconify-icon>
						{{ $pagetitle }}
					</h1>
					<div class="inner-content">
						{{ $slot }}
					</div>
				</div>
			</div>

			@else

			{{ $slot }}

			@endauth
		</div>

		@stack("modals")

		@livewireScripts

		@if (session("toast_info") || session("toast_success"))
		<script>
			DialogMessageToast(
				{{ session("toast_info") ? 0 : 1 }},
				@json(session("toast_info") ?? session("toast_success"))
			);
		</script>
		@endif
	</body>
</html>