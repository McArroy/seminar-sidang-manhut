@php
	$InputSearch = $_GET["search"] ?? "";
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="/assets/css/pages/datatables.css?v=1.0">
	@endsection

	<x-slot name="title">Data Dosen</x-slot>
	<x-slot name="icon">fontisto:person</x-slot>
	<x-slot name="pagetitle">Data Dosen</x-slot>
	
	<div class="top">
		<x-input-wrapper class="search type-2" id="search" type="text" placeholder="Cari Data" value="{{ $InputSearch }}" autofocus />
		<x-button class="add" id="add-lecturer" icon="material-symbols:add-rounded" iconwidth="auto" data-link="{{ route('admin.lecturers.add') }}">Tambah Data</x-button>
	</div>
	<div class="middle">
		<table class="type-2">
			<thead>
				<tr>
					<th class="numbered">No</th>
					<th>NIP</th>
					<th>Nama</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($dataUsers as $index => $item)
				<tr>
					<td class="numbered"></td>
					<td class="number">{{ $item->useridnumber }}</td>
					<td class="name">{{ $item->username }}</td>
					<td class="button-actions">
						<x-button class="edit" id="edit-lecturer" data-link="{{ route('admin.lecturers.update', [$item->userid]) }}">Ubah</x-button>
						<form id="form-delete" action="{{ route('admin.lecturers.delete', [$item->userid]) }}" method="POST">
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
</x-app-layout>