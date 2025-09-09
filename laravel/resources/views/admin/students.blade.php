@php
	$querySearch = request()->query("search") ?? "";
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="{{ \App\Http\Controllers\HelperController::Asset('assets/css/pages/datatables.css') }}">
	@endsection

	<x-slot name="title">Data Mahasiswa</x-slot>
	<x-slot name="icon">heroicons:user-group-solid</x-slot>
	<x-slot name="pagetitle">Data Mahasiswa</x-slot>
	
	<div class="top">
		<x-input-wrapper class="search type-2" id="search" type="text" placeholder="Cari Data Mahasiswa" value="{{ $querySearch }}" autofocus />
		<x-button class="add" id="add-student" icon="material-symbols:add-rounded" iconwidth="auto">Tambah Data</x-button>
	</div>
	<div class="middle">
		<table class="type-2">
			<thead>
				<tr>
					<th class="numbered">No</th>
					<th class="number">NIM</th>
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
						<x-button class="edit" id="edit-student" data-link="{{ $item->userid }}" data-active="{{ $item->is_active }}">Ubah</x-button>
						<form id="form-delete-user" action="{{ route('admin.students.delete', [$item->userid]) }}" method="POST">
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
		$(document).on("click", "button#add-student", function()
		{
			return DialogInputData("{{ route('admin.students.add') }}", "heroicons:user-group-solid", "Tambah Data Mahasiswa", "POST",
			`
				<x-input-wrapper id="useridnumber" type="text" label="NIM" placeholder="Masukkan NIM Mahasiswa" required />
				<x-input-wrapper id="username" type="text" label="Nama" placeholder="Masukkan Nama Mahasiswa" required />
				<x-input-wrapper class="password" id="password" type="password" label="Kata Sandi" placeholder="********" required>
					<iconify-icon icon="basil:eye-outline" class="show-hide-password" width="24" onclick="TogglePassword($(this))"></iconify-icon>
				</x-input-wrapper>
				<x-input-wrapper id="is_active" type="checkbox" label="Aktif" checked />
			`);
		});

		$(document).on("click", "button#edit-student", function()
		{
			const $userIdNumber = $(this).closest("tr").find("td.number").text().trim();
			const $userName = $(this).closest("tr").find("td.name").text().trim();

			DialogInputData("{{ route('admin.students.update', ':id') }}".replace(":id", $(this).data("link")), "heroicons:user-group-solid", "Ubah Data Mahasiswa", "POST",
			`
				<x-input-wrapper id="useridnumber" type="text" label="NIM" placeholder="Masukkan NIM Mahasiswa" value="${$userIdNumber}" readonly />
				<x-input-wrapper id="username" type="text" label="Nama" placeholder="Masukkan Nama Mahasiswa" value="${$userName}" required />
				<x-input-wrapper class="password" id="password" type="password" label="Kata Sandi" placeholder="********">
					<iconify-icon icon="basil:eye-outline" class="show-hide-password" width="24" onclick="TogglePassword($(this))"></iconify-icon>
				</x-input-wrapper>
				<x-input-wrapper id="is_active" type="checkbox" label="Aktif" />
			`);

			$("input#is_active").prop("checked", $(this).data("active") == 1);

			ValidateForms();
		});
	</script>
</x-app-layout>