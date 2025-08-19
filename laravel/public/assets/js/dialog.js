function DialogMessage($Type = 0, $DialogMessageText = ["Warning!", ""], $DialogMessageButtonText = ["Close", "OK"], $Functions = [null, null])
{
	let $IconAnimation = "/assets/anim/warning.json?hash=elQsa";
	let $ConfirmOK = "hidden";

	if ($Type == 0)	// Info
	{
		$IconAnimation = "/assets/anim/warning.json?hash=elQsa";
		$ConfirmOK = "hidden";
	}
	else if ($Type == 1)	// Confirmation
	{
		$IconAnimation = "/assets/anim/warning.json?hash=elQsa";
		$ConfirmOK = "active";
	}
	else if ($Type == 2)	// Success
	{
		$IconAnimation = "/assets/anim/success.json?hash=elQsa";
		$ConfirmOK = "hidden";
	}

	const $Content =
	`
		<lottie-player src="${$IconAnimation}" class="icon" background="transparent" speed="1" autoplay></lottie-player>
		<h1 class="title">${$DialogMessageText[0]}</h1>
		<p class="description">${$DialogMessageText[1]}</p>
		<div class="buttons">
			<button class="confirmation-close button">${$DialogMessageButtonText[0]}</button>
			<button class="confirmation-ok button ${$ConfirmOK}">${$DialogMessageButtonText[1]}</button>
		</div>
	`;
	const DialogMessage = CreateDialog($Content, "dialog-message");
	const DialogMessageEl = DialogMessage[0];

	DialogMessage.find(".confirmation-close").off("click").on("click", function()
	{
		DialogMessageEl.close();
		RemoveElement(DialogMessage);

		if (typeof $Functions[0] === "function")
			$Functions[0]();
	});

	DialogMessage.find(".confirmation-ok").off("click").on("click", function()
	{
		DialogMessageEl.close();
		RemoveElement(DialogMessage);

		if (typeof $Functions[1] === "function")
			$Functions[1]();
	});
}

function DialogMessageToast($Type = 0, $DialogMessageText = "Warning!", $Duration = 4000)
{
	let $Class = "warning";
	let $IconAnimation = "/assets/anim/warning.json?hash=elQsa";

	if ($Type == 0)	// Info
	{
		$Class = "warning";
		$IconAnimation = "/assets/anim/warning.json?hash=elQsa";
	}
	else if ($Type == 1)	// Success
	{
		$Class = "success";
		$IconAnimation = "/assets/anim/success.json?hash=elQsa";
	}

	const $FirstChild = $("body").children().first();
	let $ToastContainer = $(".toast-container");
	const $Toast =
	$(`
		<toast class="${$Class}">
			<lottie-player src="${$IconAnimation}" class="icon" background="transparent" speed="1" autoplay></lottie-player>
			<h1 class="message">${$DialogMessageText}</h1>
			<iconify-icon icon="carbon:close-filled" class="button" width="21" onclick=\"DialogMessageToastClose($(this).closest('toast'));\"></iconify-icon>
			<div class="progress"></div>
		</toast>
	`);

	if ($ToastContainer.length === 0)
	{
		$ToastContainer = $("<div class='toast-container'></div>");

		if ($FirstChild.length > 0)
			$ToastContainer.insertBefore($FirstChild);
		else
			$("body").append($ToastContainer);
	}

	$ToastContainer.append($Toast);

	$Toast.addClass("active");

	$Toast.find(".progress").css(
	{
		transition: `width ${$Duration}ms linear`,
		width: "100%"
	}).width(0);

	setTimeout(function()
	{
		$Toast.removeClass("active").addClass("hide");

		setTimeout(function()
		{
			RemoveElement($Toast);

			if ($ToastContainer.children().length === 0)
				RemoveElement($ToastContainer);
		}, 400);
	}, $Duration);
}

function DialogMessageToastClose($Toast)
{
	$Toast.removeClass("active").addClass("hide");

	setTimeout(function()
	{
		RemoveElement($Toast);
	}, 400);
}

function CreateDialog($Content, $Class = "")
{
	const $Dialog = $(`<dialog class="${$Class}"></dialog>`).html($Content);
	const $FirstChild = $("body").children().first();

	if ($FirstChild.length > 0)
		$Dialog.insertBefore($FirstChild);
	else
		$("body").append($Dialog);

	$Dialog[0].showModal();

	$("select.select2").select2(
	{
		dropdownParent: $Dialog
	});

	$Dialog.on("keydown", function(event)
	{
		if (event.key === "Escape")
		{
			$Dialog[0].close();
			$Dialog.remove();
		}
	});

	ValidateForms();

	return $Dialog;
}