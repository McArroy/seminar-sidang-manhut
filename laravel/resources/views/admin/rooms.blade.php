@php
	$querySearch = request()->query("search") ?? "";
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="{{ \App\Http\Controllers\HelperController::Asset('assets/css/pages/datatables.css') }}">
	@endsection

	<x-slot name="title">Data Ruangan</x-slot>
	<x-slot name="icon">mdi:door</x-slot>
	<x-slot name="pagetitle">Data Ruangan</x-slot>
	
	<div class="top">
		<x-input-wrapper class="search type-2" id="search" type="text" placeholder="Cari Data Ruangan" value="{{ $querySearch }}" autofocus />
		<x-button class="add" id="add-room" icon="material-symbols:add-rounded" iconwidth="auto">Tambah Data</x-button>
	</div>
	<div class="middle">
		<table class="type-2">
			<thead>
				<tr>
					<th class="numbered">No</th>
					<th class="name">Nama Ruangan</th>
					<th class="actions">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($dataRooms as $index => $item)
				<tr>
					<td class="numbered"></td>
					<td class="name">{{ $item->roomname }}</td>
					<td class="button-actions">
						<x-button class="edit" id="edit-room" data-link="{{ $item->roomid }}">Ubah</x-button>
						<form id="form-delete" action="{{ route('admin.rooms.delete', [$item->roomid]) }}" method="POST">
							@csrf
							@method("DELETE")
							
							<x-button class="remove">Hapus</x-button>
						</form>
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="3" class="not-found">Tidak Ada Data Yang Ditemukan</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>

	<x-navigator-buttons :data="$dataRooms" />
	
	<script>
		$(document).on("click", "button#add-room", function()
		{
			return DialogInputData("{{ route('admin.rooms.add') }}", "Tambah", "POST",
			`
				<x-input-wrapper id="roomname" type="text" label="Nama Ruangan" placeholder="Masukkan Nama Ruangan" required />
			`);
		});

		$(document).on("click", "button#edit-room", function()
		{
			const $roomname = $(this).closest("tr").find("td.name").text().trim();

			return DialogInputData("{{ route('admin.rooms.update', ':id') }}".replace(":id", $(this).data("link")), "Ubah", "POST",
			`
				<x-input-wrapper id="roomname" type="text" label="Nama Ruangan" placeholder="Masukkan Nama Ruangan" value="${$roomname}" required />
			`);
		});
	</script>
</x-app-layout>


