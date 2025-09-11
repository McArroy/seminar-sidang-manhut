@php
	$queryRole = request()->query("role") ?? "admin";
	$querySearch = request()->query("search") ?? "";
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="{{ \App\Http\Controllers\HelperController::Asset('assets/css/pages/datatables.css') }}">
	@endsection

	<x-slot name="title">{{ __("user." . $queryRole . ".data") }}</x-slot>
	<x-slot name="icon">
		@php

		if ($queryRole === "admin")
			$icon = "fluent:form-28-regular";
		else if ($queryRole === "student")
			$icon = "heroicons:user-group-solid";
		else if ($queryRole === "lecturer")
			$icon = "fontisto:person";

		@endphp

		{{ $icon }}
	</x-slot>
	<x-slot name="pagetitle">{{ __("user." . $queryRole . ".data") }}</x-slot>
	
	<div class="top">
		<x-input-wrapper class="search type-2" id="search" type="text" placeholder="{{ __('user.' . $queryRole . '.searchdata') }}" value="{{ $querySearch }}" autofocus />
		<x-button class="add" id="add-user" icon="material-symbols:add-rounded" iconwidth="auto">Tambah Data</x-button>
	</div>
	<div class="middle">
		<table class="type-2">
			<thead>
				<tr>
					<th class="numbered">No</th>
					<th class="number">{{ __("user." . $queryRole . ".useridnumber") }}</th>
					<th class="name">Nama</th>
					<th class="status">Status</th>
					<th class="actions">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@if ($queryRole === "admin")
					@php
					$currentAdmin = $dataUsers->firstWhere("userid", $currentUser->userid);
					@endphp

					@if ($currentAdmin)
					<tr class="highlighted">
						<td class="numbered"></td>
						<td class="number">{{ strtoupper($currentAdmin->useridnumber) }}</td>
						<td class="name">{{ $currentAdmin->username }}</td>
						<td class="status">
							@if ($currentAdmin->is_active === 1)
							<span class="status verified">Aktif</span>
							@elseif ($currentAdmin->is_active === 0)
							<span class="status rejected">Nonaktif</span>
							@endif
						</td>
						<td class="button-actions">
							<x-button class="edit" id="edit-user" data-link="{{ $currentAdmin->userid }}" data-active="{{ $currentAdmin->is_active }}">Ubah</x-button>
							<x-button class="remove" disabled>Hapus</x-button>
						</td>
					</tr>
					@endif
				@endif

				@forelse ($dataUsers as $index => $item)
					@if ($item->userid !== $currentUser->userid)
					<tr>
						<td class="numbered"></td>
						<td class="number">{{ strtoupper($item->useridnumber) }}</td>
						<td class="name">{{ $item->username }}</td>
						<td class="status">
							@if ($item->is_active === 1)
							<span class="status verified">Aktif</span>
							@elseif ($item->is_active === 0)
							<span class="status rejected">Nonaktif</span>
							@endif
						</td>
						<td class="button-actions">
							<x-button class="edit" id="edit-user" data-link="{{ $item->userid }}" data-active="{{ $item->is_active }}">Ubah</x-button>
							<form id="form-delete-user" action="{{ route('admin.users.delete', ['role' => $queryRole, $item->userid]) }}" method="POST">
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
		$(document).on("click", "button#add-user", function()
		{
			return DialogInputData("{{ route('admin.users.add', ['role' => $queryRole]) }}", "{{ $icon }}", "{{ __('user.' . $queryRole . '.adddata') }}", "POST",
			`
				<x-input-wrapper id="useridnumber" type="text" label="{{ __('user.' . $queryRole . '.useridnumber') }}" placeholder="{{ __('user.' . $queryRole . '.useridnumberplaceholder') }}" required />
				<x-input-wrapper id="username" type="text" label="{{ __('user.' . $queryRole . '.username') }}" placeholder="{{ __('user.' . $queryRole . '.usernameplaceholder') }}" required />
				<x-input-wrapper class="password" id="password" type="password" label="{{ __('common.password.text') }}" placeholder="********" required>
					<iconify-icon icon="basil:eye-outline" class="show-hide-password" width="24" onclick="TogglePassword($(this))"></iconify-icon>
				</x-input-wrapper>
				<x-input-wrapper id="is_active" type="select" label="{{ __('user.' . $queryRole . '.status') }}" placeholder="{{ __('user.' . $queryRole . '.statusplaceholder') }}">
					<option value="1" selected>Aktif</option>
					<option value="0">Nonaktif</option>
				</x-input-wrapper>
			`);
		});

		$(document).on("click", "button#edit-user", function()
		{
			const $userIdNumber = $(this).closest("tr").find("td.number").text().trim();
			const $userName = $(this).closest("tr").find("td.name").text().trim();

			DialogInputData("{{ route('admin.users.update', ['role' => $queryRole, ':id']) }}".replace(":id", $(this).data("link")), "{{ $icon }}", "{{ __('user.' . $queryRole . '.editdata') }}", "POST",
			`
				<x-input-wrapper id="useridnumber" type="text" label="{{ __('user.' . $queryRole . '.useridnumber') }}" placeholder="{{ __('user.' . $queryRole . '.useridnumberplaceholder') }}" value="${$userIdNumber}" readonly />
				<x-input-wrapper id="username" type="text" label="{{ __('user.' . $queryRole . '.username') }}" placeholder="{{ __('user.' . $queryRole . '.useridnameplaceholder') }}" value="${$userName}" required />
				<x-input-wrapper class="password" id="password" type="password" label="{{ __('common.password.text') }}" placeholder="********">
					<iconify-icon icon="basil:eye-outline" class="show-hide-password" width="24" onclick="TogglePassword($(this))"></iconify-icon>
				</x-input-wrapper>
				<x-input-wrapper id="is_active" type="select" label="{{ __('user.' . $queryRole . '.status') }}" placeholder="{{ __('user.' . $queryRole . '.statusplaceholder') }}">
					<option value="1">Aktif</option>
					<option value="0">Nonaktif</option>
				</x-input-wrapper>
			`);

			$("select#is_active").val(String($(this).data("active")));

			ValidateForms();
		});
	</script>
</x-app-layout>