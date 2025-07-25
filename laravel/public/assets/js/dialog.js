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
	let $IconAnimation = "/resource/anim/warning.json?hash=elQsa";

	if ($Type == 0)	// Info
	{
		$Class = "warning";
		$IconAnimation = "/resource/anim/warning.json?hash=elQsa";
	}
	else if ($Type == 1)	// Success
	{
		$Class = "success";
		$IconAnimation = "/resource/anim/success.json?hash=elQsa";
	}

	const $FirstChild = $("body").children().first();
	let $ToastContainer = $(".toast-container");
	const $Toast =
	$(`
		<toast class="${$Class}">
			<lottie-player src="${$IconAnimation}" class="icon" background="transparent" speed="1" autoplay></lottie-player>
			<h1 class="message">${$DialogMessageText}</h1>
			<iconify-icon icon='carbon:close-filled' class='button' width='21' onclick=\"DialogMessageToastClose($(this).closest('toast'));\"></iconify-icon>
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

function DialogInputData($Type = 0, $Forms = ["", "", "POST"], ...$Elements)
{
	let $Icon;
	let $InnerContent;
	let $SubmitCallback;

	if ($Type === 0 || $Type === 1)
	{
		$Icon = "heroicons:user-group-solid";
		$InnerContent =
		`
			<div class='input-wrapper'>
				<label for='number'>${$Type === 0 ? 'NIM' : 'NIP'}</label>
				<input type='text' id='number' name='number' placeholder='Masukkan ${$Type === 0 ? 'NIM Mahasiswa' : 'NIP Dosen'}' value='${$Elements[1] || ''}' required>
			</div>
			<div class='input-wrapper'>
				<label for='username'>Nama</label>
				<input type='text' id='username' name='username' placeholder='Masukkan Nama ${$Type === 0 ? 'Mahasiswa' : 'Dosen'}' value='${$Elements[2] || ''}' required>
			</div>
			<div class='input-wrapper password'>
				<label for='password'>Kata Sandi</label>
				<input id='password' name='password' type='password' placeholder='*******' required>
				<iconify-icon icon='basil:eye-outline' class='show-hide-password' width='24' onclick='TogglePassword($(this))'></iconify-icon>
			</div>
		`;
	}
	else if ($Type === 2)
	{
		$Icon = "basil:document-outline";
		$InnerContent =
		`
			<div class='input-wrapper'>
				<label for='comment'>Komentar</label>
				<textarea/ type='text' id='comment' name='comment' placeholder='Masukkan saran revisi Anda' required></textarea>
			</div>
		`;
	}
	else if ($Type === 3 || $Type === 4)
	{
		$Icon = "heroicons:user-group-solid";
		$InnerContent =
		`
			<div class='input-wrapper'>
				<label for='number_letter'>Nomor Surat</label>
				<input type='text' id='number_letter' name='number_letter' placeholder='Masukkan Nomor Surat' required>
			</div>
			<div class='input-wrapper'>
				<label for='moderator'>Moderator</label>
				<select name='moderator' id='moderator' required>
					<option value='' disabled selected hidden>Pilih Dosen Moderator</option>
					<option value='Dosen 1 - 11X1234567890'>Dosen Moderator 1</option>
					<option value='Dosen 2 - 12X1234567890'>Dosen Moderator 2</option>
					<option value='Dosen 3 - 13X1234567890'>Dosen Moderator 3</option>
				</select>
			</div>
			<div class='input-wrapper'>
				<label for='date'>Tanggal Pembuatan</label>
				<input type='date' id='date' name='date' required>
			</div>
		`;

		if ($Type === 4)
			$InnerContent +=
			`
				<div class='input-wrapper'>
					<label for='supervisory_committee'>Ketua Komisi Pembimbing</label>
					<select name='supervisory_committee' id='supervisory_committee' required>
						<option value='' disabled selected hidden>Pilih Ketua Komisi Pembimbing</option>
						<option value='Dosen 1 - 11X1234567890'>Ketua Komisi Pembimbing 1</option>
						<option value='Dosen 2 - 12X1234567890'>Ketua Komisi Pembimbing 2</option>
						<option value='Dosen 3 - 13X1234567890'>Ketua Komisi Pembimbing 3</option>
					</select>
				</div>
				<div class='input-wrapper'>
					<label for='external_examiner'>Penguji Luar Komisi</label>
					<input type='text' id='external_examiner' name='external_examiner' placeholder='Masukkan Nama Penguji Luar Komisi' required>
				</div>
				<div class='input-wrapper'>
					<label for='chairman_session'>Ketua Sidang</label>
					<select name='chairman_session' id='chairman_session' required>
						<option value='' disabled selected hidden>Pilih Ketua Sidang</option>
						<option value='Dosen 1 - 11X1234567890'>Ketua Sidang 1</option>
						<option value='Dosen 2 - 12X1234567890'>Ketua Sidang 2</option>
						<option value='Dosen 3 - 13X1234567890'>Ketua Sidang 3</option>
					</select>
				</div>
			`;
	}

	const $Content =
	`
		<form action='${$Forms[1]}' method='${$Forms[2]}' onsubmit=\"${$SubmitCallback}\">
			<div class='top'>
				<img src='/resource/img/background-banner.png' alt='background-banner'>
				<div class='text'><iconify-icon icon='${$Icon}' width='24'></iconify-icon>${$Elements[0]}</div>
			</div>
			<div class='content'>
				${$InnerContent}
				<div class='buttons'>
					<button class='button confirmation-close' onclick=\"RemoveElement(this.closest('dialog'));\">Batal</button>
					<button class='button confirmation-ok active'>Simpan</button>
				</div>
			</div>
		</form>
	`;

	CreateDialog($Content, "input-data " + $Forms[0]);
}

function CreateDialog($Content, $Class = "")
{
	const $Dialog = $(`<dialog class='${$Class}'></dialog>`).html($Content);
	const $FirstChild = $("body").children().first();

	if ($FirstChild.length > 0)
		$Dialog.insertBefore($FirstChild);
	else
		$("body").append($Dialog);

	$Dialog[0].showModal();

	return $Dialog;
}