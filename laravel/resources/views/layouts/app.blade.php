<?php

date_default_timezone_set("Asia/Jakarta");

$HashFile = "Uvuvwevwevwe Onyetenyevwe Ugwemuhwem Osas";

$PageName = "";

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
		<link rel="stylesheet" href="/assets/css/elements/scrollbar.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/elements/tables.css?hash=<?= $HashFile ?>">

		@guest
		<link rel="stylesheet" href="/assets/css/pages/login.css?hash=<?= $HashFile ?>">
		@else
		@yield("css")
		<?php if ($PageName === "Requirements"): ?>
		<link rel="stylesheet" href="/assets/css/pages/registrationform.css?hash=<?= $HashFile ?>">
		<link rel="stylesheet" href="/assets/css/pages/seminarrequirements.css?hash=<?= $HashFile ?>">
		<?php elseif ($PageName === "Schedule"): ?>
		<link rel="stylesheet" href="/assets/css/pages/schedule.css?hash=<?= $HashFile ?>">
		<?php elseif ($PageName === "Students" || $PageName === "Lecturers" || $PageName === "Seminar" || $PageName === "Announcements" || $PageName === "Thesis Defense"): ?>
		<link rel="stylesheet" href="/assets/css/pages/datatables.css?hash=<?= $HashFile ?>">
		<?php endif; ?>
		@endguest

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
				<img src="/resource/img/user.png?hash=<?= $HashFile ?>" alt="user-image">
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
				@if (Auth::user()->userrole === "admin")

				@else

				<x-nav-link href="{{ route('student.dashboard') }}" :active="request()->routeIs('student.dashboard')">
					<iconify-icon icon="material-symbols:dashboard-outline" width="21"></iconify-icon>
					Dashboard
				</x-nav-link>

				<h1>Menu Utama</h1>

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

				<x-nav-link href="{{ route('student.flow', ['type' => 'thesisdefense']) }}" class="button-list" :active="request()->routeIs('student.flow') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.registrationform') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.requirements') && request()->query('type') === 'thesisdefense'" onclick="ToggleButtonList($(this))">
					<iconify-icon icon="streamline-flex:presentation" width="21"></iconify-icon>
					Daftar Sidang Akhir
					<iconify-icon icon="weui:arrow-filled" width="12"></iconify-icon>
				</x-nav-link>
				<x-nav-link-dropdown :active="request()->routeIs('student.flow') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.registrationform') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.requirements') && request()->query('type') === 'thesisdefense'">
					<x-nav-link href="{{ route('student.registrationform', ['type' => 'thesisdefense']) }}" :active="request()->routeIs('student.registrationform') && request()->query('type') === 'thesisdefense'">
						<iconify-icon icon="basil:document-outline" width="21"></iconify-icon>
						Form Pendaftaran
					</x-nav-link>
					<x-nav-link href="{{ route('student.requirements', ['type' => 'thesisdefense']) }}" :active="request()->routeIs('student.requirements') && request()->query('type') === 'thesisdefense'">
						<iconify-icon icon="hugeicons:folder-upload" width="21"></iconify-icon>
						Persyaratan Sidang
					</x-nav-link>
				</x-nav-link-dropdown>

				<x-nav-link href="{{ route('student.schedule') }}" :active="request()->routeIs('student.schedule')">
					<iconify-icon icon="material-symbols-light:calendar-clock-outline-sharp" width="21"></iconify-icon>
					Jadwal
				</x-nav-link>
				
				@endif

				<form action="{{ route('logout') }}" method="POST" onsubmit="return FormConfirmation(event, ['Anda Yakin Akan Mengakhiri Sesi?', 'Sesi anda akan berakhir dan silahkan memulai sesi baru'], ['Batal', 'Keluar']);">
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

		<div class="min-h-screen bg-gray-100">
			<!-- Page Heading -->
			@if (isset($header))
				<header class="bg-white shadow">
					<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
						{{ $header }}
					</div>
				</header>
			@endif
		</div>

		@stack("modals")

		@livewireScripts
	</body>
</html>