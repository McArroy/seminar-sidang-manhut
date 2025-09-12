@php
	$querySearch = request()->query("search") ?? "";
	$queryType = request()->query("type") ?? "seminar";
@endphp

<x-app-layout>
	@section("css")
		<link rel="stylesheet" href="{{ \App\Http\Controllers\HelperController::Asset('assets/css/pages/datatables.css') }}">
	@endsection

	<x-slot name="title">
        @if ($queryType === "seminar")
            Pelaksanaan Seminar
        @else
            Pelaksanaan Sidang
        @endif
    </x-slot>
	<x-slot name="icon">fluent:presenter-20-regular</x-slot>
	<x-slot name="pagetitle">
        @if ($queryType === "seminar")
            Pelaksanaan Seminar
        @else
            Pelaksanaan Sidang
        @endif
    </x-slot>
	
	<div class="top">
		<x-input-wrapper class="search type-2" id="search" type="text" placeholder="Cari Data" value="{{ $querySearch }}" autofocus />
	</div>
	<div class="middle">
		<table class="type-2">
			<thead>
				<tr>
					<th class="numbered">No</th>
					<th class="number">NIM</th>
					<th class="name">Nama Mahasiswa</th>
					<th class="action">Dokumen</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($academics as $index => $item)
				<tr>
					<td class="numbered"></td>
					<td class="number">{{ strtoupper($item->useridnumber) }}</td>
					<td class="name">{!! $item->username !!}</td>
					<td class="action">
						<div class="action-buttons">
							@if ($queryType === "thesisdefense")
								<a href="{{ route('admin.execution', ['useridnumber' => $item->useridnumber, 'type' => $queryType]) }}" class="buttons" target="_blank">
									BAP
								</a>
								<a href="{{ route('admin.execution', ['useridnumber' => $item->useridnumber, 'type' => $queryType]) }}" class="buttons" target="_blank">
									Nilai Penguji
								</a>
								<a href="{{ route('admin.execution', ['useridnumber' => $item->useridnumber, 'type' => $queryType]) }}" class="buttons" target="_blank">
									Nilai Pembimbing
								</a>
								<a href="{{ route('admin.execution', ['useridnumber' => $item->useridnumber, 'type' => $queryType]) }}" class="buttons" target="_blank">
									Perbaikan
								</a>
							@else
								<a href="{{ route('admin.execution', ['useridnumber' => $item->useridnumber, 'type' => $queryType]) }}" class="buttons" target="_blank">
									BAP
								</a>
								<a href="{{ route('admin.execution', ['useridnumber' => $item->useridnumber, 'type' => $queryType]) }}" class="buttons" target="_blank">
									Nilai
								</a>
							@endif
						</div>
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

	<x-navigator-buttons :data="$academics" />
	
</x-app-layout>