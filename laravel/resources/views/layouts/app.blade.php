<?php

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
		<link href="https://cdn.jsdelivr.net/npm/select2@latest/dist/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdn.jsdelivr.net/npm/jquery@latest/dist/jquery.min.js"></script>
		<script src="https://code.iconify.design/iconify-icon/3.0.0/iconify-icon.min.js"></script>
		<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/html2pdf.js@latest/dist/html2pdf.bundle.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/chart.umd.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/select2@latest/dist/js/select2.min.js"></script>
		
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
					<h1>{{ config("app.name") }}</h1>
					<h1>Sistem Akademik Manajemen Hutan</h1>
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
			<navbar class="side">
				<x-nav-link href="{{ route(Auth::user()->userrole . '.dashboard') }}" icon="material-symbols:dashboard-outline" iconwidth="21" :active="request()->routeIs(Auth::user()->userrole . '.dashboard')">
					Dashboard
				</x-nav-link>

				<h1>Menu Utama</h1>

				@if (Auth::user()->userrole === "admin")

				<x-nav-link href="{{ route('admin.students') }}" icon="heroicons:user-group-solid" iconwidth="21" :active="request()->routeIs('admin.students')">
					Data Mahasiswa
				</x-nav-link>
				<x-nav-link href="{{ route('admin.lecturers') }}" icon="fontisto:person" iconwidth="15" :active="request()->routeIs('admin.lecturers')">
					Data Dosen
				</x-nav-link>
				<x-nav-link href="{{ route('admin.rooms') }}" icon="mdi:door" iconwidth="21" :active="request()->routeIs('admin.rooms')">
					Data Ruangan
				</x-nav-link>
				<x-nav-link href="{{ route('admin.seminars') }}" icon="fluent:form-28-regular" iconwidth="21" :active="request()->routeIs('admin.seminars')">
					Seminar
				</x-nav-link>
				<x-nav-link href="{{ route('admin.announcements', ['type' => 'seminar']) }}"  icon="hugeicons:folder-upload" iconwidth="21" :active="request()->routeIs('admin.announcements') && request()->query('type') === 'seminar'">
					Pengumuman Seminar
				</x-nav-link>
				<x-nav-link href="{{ route('admin.thesisdefenses') }}" icon="streamline-flex:presentation" iconwidth="21" :active="request()->routeIs('admin.thesisdefenses')">
					Sidang Akhir
				</x-nav-link>
				<x-nav-link href="{{ route('admin.announcements', ['type' => 'thesisdefense']) }}" icon="hugeicons:folder-upload" iconwidth="21" :active="request()->routeIs('admin.announcements') && request()->query('type') === 'thesisdefense'">
					Undangan Sidang
				</x-nav-link>

				@elseif (Auth::user()->userrole === "student")

				<x-nav-link href="{{ route('student.flow', ['type' => 'seminar']) }}" class="button-list" icon="fluent:form-28-regular" iconwidth="21" :active="request()->routeIs('student.flow') && request()->query('type') === 'seminar' || request()->routeIs('student.registrationform') && request()->query('type') === 'seminar' || request()->routeIs('student.registrationletter') && request()->query('type') === 'seminar' || request()->routeIs('student.requirements') && request()->query('type') === 'seminar'" onclick="ToggleButtonList($(this))">
					Daftar Seminar
				</x-nav-link>
				<x-nav-link-dropdown :active="request()->routeIs('student.flow') && request()->query('type') === 'seminar' || request()->routeIs('student.registrationform') && request()->query('type') === 'seminar' || request()->routeIs('student.registrationletter') && request()->query('type') === 'seminar' || request()->routeIs('student.requirements') && request()->query('type') === 'seminar'">
					<x-nav-link href="{{ route('student.registrationform', ['type' => 'seminar']) }}" icon="basil:document-outline" iconwidth="21" :active="request()->routeIs('student.registrationform') && request()->query('type') === 'seminar' || request()->routeIs('student.registrationletter') && request()->query('type') === 'seminar'">
						Form Pendaftaran
					</x-nav-link>
					<x-nav-link href="{{ route('student.requirements', ['type' => 'seminar']) }}" icon="hugeicons:folder-upload" iconwidth="21" :active="request()->routeIs('student.requirements') && request()->query('type') === 'seminar'">
						Persyaratan Seminar
					</x-nav-link>
				</x-nav-link-dropdown>

				<x-nav-link href="{{ route('student.flow', ['type' => 'thesisdefense']) }}" class="button-list" icon="streamline-flex:presentation" iconwidth="21" :active="request()->routeIs('student.flow') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.registrationform') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.registrationletter') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.requirements') && request()->query('type') === 'thesisdefense'" onclick="ToggleButtonList($(this))">
					Daftar Sidang Akhir
				</x-nav-link>
				<x-nav-link-dropdown :active="request()->routeIs('student.flow') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.registrationform') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.registrationletter') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.requirements') && request()->query('type') === 'thesisdefense'">
					<x-nav-link href="{{ route('student.registrationform', ['type' => 'thesisdefense']) }}" icon="basil:document-outline" iconwidth="21" :active="request()->routeIs('student.registrationform') && request()->query('type') === 'thesisdefense' || request()->routeIs('student.registrationletter') && request()->query('type') === 'thesisdefense'">
						Form Pendaftaran
					</x-nav-link>
					<x-nav-link href="{{ route('student.requirements', ['type' => 'thesisdefense']) }}" icon="hugeicons:folder-upload" iconwidth="21" :active="request()->routeIs('student.requirements') && request()->query('type') === 'thesisdefense'">
						Persyaratan Sidang
					</x-nav-link>
				</x-nav-link-dropdown>
				
				@endif

				<x-nav-link href="{{ route(Auth::user()->userrole . '.schedule') }}" icon="material-symbols-light:calendar-clock-outline-sharp" iconwidth="21" :active="request()->routeIs(Auth::user()->userrole . '.schedule')">
					Jadwal
				</x-nav-link>

				<form id="form-logout" action="{{ route('logout') }}" method="POST">
					@csrf

					<x-button href="javascript:void(0);" icon="material-symbols:logout-rounded" iconwidth="21" class="button button-logout">
						Keluar
					</x-button>
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

		<script>
			function DialogInputData($path = "{{ url()->current() }}", $icon = "heroicons:user-group-solid", $title = "", $type = "POST", $innerContent = "")
			{
				const $content = 
				`<form action="${$path}" method="${$type}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="_method" value="${$type}">

					<div class="top">
						<img src="/assets/img/background-banner.png" alt="background-banner">
						<div class="text">
							<iconify-icon icon="${$icon}" width="24"></iconify-icon>
							${$title}
						</div>
					</div>
					<div class="content">
						${$innerContent}
						<div class="buttons">
							<x-button class="confirmation-close">Batal</x-button>
							<x-button class="confirmation-ok active">Simpan</x-button>
						</div>
					</div>
				</form>`;

				CreateDialog($content, "input-data");
			}
		</script>
	</body>
</html>