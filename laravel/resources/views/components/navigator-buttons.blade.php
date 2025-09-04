@props(["data"])

@php $queryPageSearch = request()->query("page") ?? "1"; @endphp

<div class="navigator-buttons">
	@if ($data->total() > 0)
	<p>Menampilkan {{ $data->firstItem() }} sampai {{ $data->lastItem() }} dari {{ $data->total() ?? 0 }} data</p>
	@else
	<p>Tidak data ada yang ditampilkan</p>
	@endif

	<div class="buttons">
		@php
			$CurrentPage = $data->currentPage();
			$LastPage = $data->lastPage();
		@endphp

		@if ($CurrentPage <= 1)
			<x-button class="previous" disabled>Sebelumnya</x-button>
		@else
			<x-button class="previous navigator-button" data-link="{{ $CurrentPage > 1 ? $CurrentPage - 1 : 1 }}">Sebelumnya</x-button>
		@endif

		@for ($i = 1; $i <= $LastPage; $i++)
			<x-button class="page navigator-button {{ $CurrentPage === $i ? 'active' : '' }}" data-link="{{ $i }}">{{ $i }}</x-button>
		@endfor
		
		@if ($CurrentPage >= $LastPage)
			<x-button class="next" disabled>Berikutnya</x-button>
		@else
			<x-button class="next navigator-button" data-link="{{ (isset($queryPageSearch) ? ((int)$queryPageSearch + 1) : 2) }}">Berikutnya</x-button>
		@endif
	</div>
</div>