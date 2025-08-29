<x-guest-layout>
	@section("css")
		<link rel="stylesheet" href="{{ \App\Http\Controllers\HelperController::Asset('assets/css/pages/login.css') }}">
	@endsection

	<x-slot name="title">Login</x-slot>
	
	<div class="left">
		<x-ipb-logo/>
		<div class="text">
			<h6>Selamat Datang di</h6>
			<h1>{{ config("app.name") }}</h1>
			<h2>Sistem Akademik Manajemen Hutan</h2>
		</div>
	</div>

	<div class="right">
		<form action="{{ route('login') }}" method="POST">
			@csrf
			@method("POST")

			<x-input-wrapper id="useridnumber" label="ID Pengguna" type="text" placeholder="Masukkan ID Pengguna" value="{{ old('useridnumber') }}" required>
				<iconify-icon icon="solar:user-bold" width="24"></iconify-icon>
			</x-input-wrapper>
			<x-input-wrapper class="password" id="password" label="Kata Sandi" type="password" placeholder="Masukkan Kata Sandi" required>
				<iconify-icon icon="carbon:password" width="24"></iconify-icon>
				<iconify-icon icon="basil:eye-outline" class="show-hide-password" width="24" onclick="TogglePassword($(this))"></iconify-icon>
			</x-input-wrapper>
			<x-button>MASUK</x-button>
		</form>

		<h6>© {{ getdate()["year"] }} Forest Management — All Rights Reserved</h6>
	</div>
</x-guest-layout>