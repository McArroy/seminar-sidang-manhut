@php
	$InputSearch = $_GET["search"] ?? "";
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="/assets/css/pages/datatables.css?v=1.0">
	@endsection

	<x-slot name="title">Data Admin</x-slot>
	<x-slot name="icon">eos-icons:admin</x-slot>
	<x-slot name="pagetitle">Data Admin</x-slot>
	
	<div class="top">
		<x-input-wrapper class="search type-2" id="search" type="text" placeholder="Cari Data Admin" value="{{ $InputSearch }}" autofocus />
		<x-button class="add" id="add-admin" icon="material-symbols:add-rounded" iconwidth="auto">Tambah Data</x-button>
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
				@php
				$currentAdmin = $dataUsers->firstWhere("userid", $currentUser->userid);
				@endphp

				@if ($currentAdmin)
				<tr class="highlighted">
					<td class="numbered"></td>
					<td class="number">{{ strtoupper($currentAdmin->useridnumber) }}</td>
					<td class="name">{{ $currentAdmin->username }}</td>
					<td class="button-actions">
						<x-button class="edit" id="edit-admin" data-link="{{ $currentAdmin->userid }}">Ubah</x-button>
						<form id="form-delete" action="{{ route('admin.admins.delete', [$currentAdmin->userid]) }}" method="POST">
							@csrf
							@method("DELETE")
							
							<x-button class="remove">Hapus</x-button>
						</form>
					</td>
				</tr>
				@endif

				@forelse ($dataUsers as $index => $item)
					@if ($item->userid !== $currentUser->userid)
					<tr>
						<td class="numbered"></td>
						<td class="number">{{ strtoupper($item->useridnumber) }}</td>
						<td class="name">{{ $item->username }}</td>
						<td class="button-actions">
							<x-button class="edit" id="edit-admin" data-link="{{ $item->userid }}">Ubah</x-button>
							<form id="form-delete" action="{{ route('admin.admins.delete', [$item->userid]) }}" method="POST">
								@csrf
								@method("DELETE")
								
								<x-button class="remove">Hapus</x-button>
							</form>
						</td>
					</tr>
					@endif
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
		$(document).on("click", "button#add-admin", function()
		{
			return DialogInputData("{{ route('admin.admins.add') }}", "Tambah", "POST",
			`
				<x-input-wrapper id="useridnumber" type="text" label="NIP" placeholder="Masukkan NIP Admin" required />
				<x-input-wrapper id="username" type="text" label="Nama" placeholder="Masukkan Nama Admin" required />
				<x-input-wrapper class="password" id="password" type="password" label="Kata Sandi" placeholder="********" required>
					<iconify-icon icon="basil:eye-outline" class="show-hide-password" width="24" onclick="TogglePassword($(this))"></iconify-icon>
				</x-input-wrapper>
			`);
		});

		$(document).on("click", "button#edit-admin", function()
		{
			const $userIdNumber = $(this).closest("tr").find("td.number").text().trim();
			const $userName = $(this).closest("tr").find("td.name").text().trim();

			return DialogInputData("{{ route('admin.admins.update', ':id') }}".replace(":id", $(this).data("link")), "Ubah", "POST",
			`
				<x-input-wrapper id="useridnumber" type="text" label="NIP" placeholder="Masukkan NIP Admin" value="${$userIdNumber}" required />
				<x-input-wrapper id="username" type="text" label="Nama" placeholder="Masukkan Nama Admin" value="${$userName}" required />
				<x-input-wrapper class="password" id="password" type="password" label="Kata Sandi" placeholder="********">
					<iconify-icon icon="basil:eye-outline" class="show-hide-password" width="24" onclick="TogglePassword($(this))"></iconify-icon>
				</x-input-wrapper>
			`);
		});
	</script>
</x-app-layout>