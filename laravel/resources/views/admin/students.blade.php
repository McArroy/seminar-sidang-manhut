@php
	$InputSearch = $_GET["search"] ?? "";
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="/assets/css/pages/datatables.css?v=1.0">
	@endsection

	<x-slot name="title">Data Mahasiswa</x-slot>
	<x-slot name="icon">heroicons:user-group-solid</x-slot>
	<x-slot name="pagetitle">Data Mahasiswa</x-slot>
	
	<div class="top">
		<x-input-wrapper class="search type-2" id="search" type="text" placeholder="Cari Data Mahasiswa" value="{{ $InputSearch }}" autofocus />
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
						<x-button class="edit" id="edit-student" data-link="{{ $item->userid }}">Ubah</x-button>
						<form id="form-delete" action="{{ route('admin.students.delete', [$item->userid]) }}" method="POST">
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
			return DialogInputData("{{ route('admin.students.add') }}", "Tambah", "POST",
			`
				<x-input-wrapper id="useridnumber" type="text" label="NIM" placeholder="Masukkan NIM Mahasiswa" oninput="$('#password').val($(this).val().toUpperCase())" required />
				<x-input-wrapper id="username" type="text" label="Nama" placeholder="Masukkan Nama Mahasiswa" required />
				<x-input-wrapper class="password" id="password" type="password" label="Kata Sandi" placeholder="********" required>
					<iconify-icon icon="basil:eye-outline" class="show-hide-password" width="24" onclick="TogglePassword($(this))"></iconify-icon>
				</x-input-wrapper>
			`);
		});

		$(document).on("click", "button#edit-student", function()
		{
			const $userIdNumber = $(this).closest("tr").find("td.number").text().trim();
			const $userName = $(this).closest("tr").find("td.name").text().trim();

			return DialogInputData("{{ route('admin.students.update', ':id') }}".replace(":id", $(this).data("link")), "Ubah", "POST",
			`
				<x-input-wrapper id="useridnumber" type="text" label="NIM" placeholder="Masukkan NIM Mahasiswa" value="${$userIdNumber}" required />
				<x-input-wrapper id="username" type="text" label="Nama" placeholder="Masukkan Nama Mahasiswa" value="${$userName}" required />
				<x-input-wrapper class="password" id="password" type="password" label="Kata Sandi" placeholder="********">
					<iconify-icon icon="basil:eye-outline" class="show-hide-password" width="24" onclick="TogglePassword($(this))"></iconify-icon>
				</x-input-wrapper>
			`);
		});
	</script>
</x-app-layout>