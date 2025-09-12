@props(["id" => "pdf-source", "title" => "PDF Viewer", "sandbox" => ""])

<div class="letter-viewer">
	<div class="letter-viewer-top">
		<p>{{ $title }}</p>
		<div class="letter-viewer-buttons">
			<x-button class="icon-fullscreen" icon="material-symbols-light:pan-zoom" iconwidth="25" onclick="return FullScreenPDFFrame($('.icon-fullscreen iconify-icon'), $('.letter-viewer'));"></x-button>
			<x-button icon="carbon:zoom-reset" iconwidth="25" onclick="return ZoomResetPDFFrame($('#{{ $id }}'));"></x-button>
			<x-button icon="iconamoon:zoom-out-light" iconwidth="25" onclick="return ZoomOutPDFFrame($('#{{ $id }}'));"></x-button>
			<x-button icon="iconamoon:zoom-in-light" iconwidth="25" onclick="return ZoomInPDFFrame($('#{{ $id }}'));"></x-button>
			<x-button icon="mynaui:download" iconwidth="25" onclick="return DownloadPDF($('#{{ $id }}'));"></x-button>
		</div>
	</div>
	<iframe id="{{ $id }}" class="viewer"  sandbox="allow-scripts allow-same-origin allow-modals" onload="return ApplyZoomPDFFrame($('#{{ $id }}'));" srcdoc="{{ $slot }}"></iframe>
</div>