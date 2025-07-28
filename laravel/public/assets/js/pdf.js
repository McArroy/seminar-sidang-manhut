let CurrentZoom = 1;
const MinZoom = .5;
const MaxZoom = 3;

function ApplyZoomPDFFrame($PDFFrame)
{
	const PDFFrame = $PDFFrame[0];
	const Doc = PDFFrame.contentDocument || PDFFrame.contentWindow.document;
	const $ZoomTarget = $(Doc.body).find(".wrapper");

	if ($ZoomTarget.length)
	{
		$ZoomTarget.css("transform", "scale(" + CurrentZoom + ")");

		// Adjust iframe height to fit scaled content
		const OriginalHeight = $ZoomTarget[0].scrollHeight || Doc.body.scrollHeight;
		$PDFFrame.css("height", (OriginalHeight * CurrentZoom) + "px");
	}
}

function ZoomInPDFFrame($PDFFrame)
{
	if (CurrentZoom < MaxZoom)
	{
		CurrentZoom += .1;
		ApplyZoomPDFFrame($PDFFrame);
	}
}

function ZoomOutPDFFrame($PDFFrame)
{
	if (CurrentZoom > MinZoom)
	{
		CurrentZoom -= .1;
		ApplyZoomPDFFrame($PDFFrame);
	}
}

function ZoomResetPDFFrame($PDFFrame)
{
	CurrentZoom = 1;
	ApplyZoomPDFFrame($PDFFrame);
}

function FullScreenPDFFrame($Icon, $Element)
{
	const IsFullscreen = $Element.data("fullscreen") === true;
	const PDFFrameFullScreenClassName = $Element.attr("class") + " dialog";
	const PDFFrameFullScreenDialog = "." + PDFFrameFullScreenClassName.trim().split(" ").join(".");

	if (IsFullscreen)
	{
		$Icon.attr("icon", "material-symbols-light:pan-zoom");
		
		if ($(PDFFrameFullScreenDialog)[0])
		{
			$(PDFFrameFullScreenDialog)[0].addEventListener("close", function()
			{
				RemoveElement($($(PDFFrameFullScreenDialog)[0]));
			}, { once: true });

			$(PDFFrameFullScreenDialog)[0].close();
			$($(PDFFrameFullScreenDialog)[0]).removeClass("active");
		}
	}
	else
	{
		$Icon.attr("icon", "material-symbols-light:hide-rounded");

		RemoveElement($(PDFFrameFullScreenDialog));

		const PDFFrameFullScreen = document.createElement("dialog");

		PDFFrameFullScreen.className = PDFFrameFullScreenClassName;
		PDFFrameFullScreen.innerHTML = $Element.html().replaceAll("pdf-source", "pdf-source-2");

		$Element.append(PDFFrameFullScreen);
		PDFFrameFullScreen.showModal();
		$(PDFFrameFullScreenDialog).addClass("active");
	}
	
	$Element.data("fullscreen", !IsFullscreen);
}

function DownloadPDF($PDFFrame)
{
	const PDFFrame = $PDFFrame[0];

	ZoomResetPDFFrame($PDFFrame);

	if (PDFFrame && PDFFrame.contentWindow)
	{
		PDFFrame.contentWindow.focus();
		PDFFrame.contentWindow.print();
	}
}