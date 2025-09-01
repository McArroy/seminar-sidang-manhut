@php
	$querySearch = request()->query("search") ?? "";
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="{{ \App\Http\Controllers\HelperController::Asset('assets/css/pages/datatables.css') }}">
	@endsection

	<x-slot name="title">Data Dosen</x-slot>
	<x-slot name="icon">fontisto:person</x-slot>
	<x-slot name="pagetitle">Data Dosen</x-slot>
	
	<div class="top">
		<x-input-wrapper class="search type-2" id="search" type="text" placeholder="Cari Data Dosen" value="{{ $querySearch }}" autofocus />
		<x-button class="add" id="add-lecturer" icon="material-symbols:add-rounded" iconwidth="auto">Tambah Data</x-button>
	</div>
	<div class="middle">
		<table class="type-2">
			<thead>
				<tr>
					<th class="numbered">No</th>
					<th class="number">NIP</th>
					<th class="name">Nama</th>
					<th class="actions">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($dataUsers as $index => $item)
				<tr>
					<td class="numbered"></td>
					<td class="number">{{ strtoupper($item->useridnumber) }}</td>
					<td class="name">{{ $item->username }}</td>
					<td class="button-actions">
						<x-button class="edit" id="edit-lecturer" data-link="{{ $item->userid }}">Ubah</x-button>
						<form id="form-delete-user" action="{{ route('admin.lecturers.delete', [$item->userid]) }}" method="POST">
							@csrf
							@method("DELETE")
							
							<x-button class="remove">Hapus</x-button>
						</form>
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="4" class="not-found">Tidak Ada Data Yang Ditemukan</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>

	<x-navigator-buttons :data="$dataUsers" />
	
	<script>
		$(document).on("click", "button#add-lecturer", function()
		{
			return DialogInputData("{{ route('admin.lecturers.add') }}", "Tambah", "POST",
			`
				<x-input-wrapper id="useridnumber" type="text" label="NIP" placeholder="Masukkan NIP Dosen" required />
				<x-input-wrapper id="username" type="text" label="Nama" placeholder="Masukkan Nama Dosen" required />
				<x-input-wrapper class="password" id="password" type="password" label="Kata Sandi" placeholder="********" required>
					<iconify-icon icon="basil:eye-outline" class="show-hide-password" width="24" onclick="TogglePassword($(this))"></iconify-icon>
				</x-input-wrapper>
			`);
		});

		$(document).on("click", "button#edit-lecturer", function()
		{
			const $userIdNumber = $(this).closest("tr").find("td.number").text().trim();
			const $userName = $(this).closest("tr").find("td.name").text().trim();

			return DialogInputData("{{ route('admin.lecturers.update', ':id') }}".replace(":id", $(this).data("link")), "Ubah", "POST",
			`
				<x-input-wrapper id="useridnumber" type="text" label="NIP" placeholder="Masukkan NIP Dosen" value="${$userIdNumber}" readonly />
				<x-input-wrapper id="username" type="text" label="Nama" placeholder="Masukkan Nama Dosen" value="${$userName}" required />
				<x-input-wrapper class="password" id="password" type="password" label="Kata Sandi" placeholder="********">
					<iconify-icon icon="basil:eye-outline" class="show-hide-password" width="24" onclick="TogglePassword($(this))"></iconify-icon>
				</x-input-wrapper>
			`);
		});
	</script>
</x-app-layout>