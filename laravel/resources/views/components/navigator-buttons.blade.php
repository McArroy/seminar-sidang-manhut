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
			$currentPage = $data->currentPage();
			$lastPage = $data->lastPage();
			$paginationRange = 2;
		@endphp

		@if ($currentPage <= 1)
			<x-button class="previous" disabled>Sebelumnya</x-button>
		@else
			<x-button class="previous navigator-button" data-link="{{ $currentPage > 1 ? $currentPage - 1 : 1 }}">Sebelumnya</x-button>
		@endif

		<x-button class="page navigator-button {{ $currentPage === 1 ? 'active' : '' }}" data-link="1">1</x-button>

		@if ($currentPage > ($paginationRange + 2))
			...
		@endif

		@for ($i = max(2, $currentPage - $paginationRange); $i <= min($lastPage - 1, $currentPage + $paginationRange); $i++)
			<x-button class="page navigator-button {{ $currentPage === $i ? 'active' : '' }}" data-link="{{ $i }}">{{ $i }}</x-button>
		@endfor
		
		@if ($currentPage < ($lastPage - $paginationRange - 1))
			...
		@endif

		@if ($lastPage > 1)
			<x-button class="page navigator-button {{ $currentPage === $lastPage ? 'active' : '' }}" data-link="{{ $lastPage }}">{{ $lastPage }}</x-button>
		@endif
		
		@if ($currentPage >= $lastPage)
			<x-button class="next" disabled>Berikutnya</x-button>
		@else
			<x-button class="next navigator-button" data-link="{{ $currentPage + 1 }}">Berikutnya</x-button>
		@endif
	</div>
</div>