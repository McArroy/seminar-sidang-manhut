<script>
@if (session("dialog_info") || session("dialog_success"))
	DialogMessage(
		{{ session("dialog_info") ? 0 : 2 }},
		[@json(session("dialog_info")[0] ?? session("dialog_success")[0]), @json(session("dialog_info")[1] ?? session("dialog_success")[1])],
		[@json(session("dialog_info")[2] ?? session("dialog_success")[2]), @json(session("dialog_info")[3] ?? session("dialog_success")[3])],
		[@json(session("dialog_info")[4] ?? session("dialog_success")[4]), @json(session("dialog_info")[5] ?? session("dialog_success")[5])]
	);
@endif

@if (session("toast_info") || session("toast_success"))
	DialogMessageToast(
		{{ session("toast_info") ? 0 : 1 }},
		@json(session("toast_info") ?? session("toast_success"))
	);
@endif

	function GetHashValue($Hash)
	{
		const PageSection = window.location.hash.substring(1);

		if ($Hash === PageSection)
			return true;

		return false;
	}

	function UpdateQueryParam($Key, $Value, ResetParams = [])
	{
		let Params = new URLSearchParams(window.location.search);

		Params.set($Key, $Value);

		ResetParams.forEach(paramKey =>
		{
			Params.delete(paramKey);
		});

		window.location.search = Params.toString();
	}

	function IsGoogleDriveUrl($Url)
	{
		const DriveRegex = /^(https?:\/\/)?(www\.)?drive\.google\.com\/(file\/d\/|open\?id=|uc\?id=|drive\/folders\/)[a-zA-Z0-9_-]+/;

		return DriveRegex.test($Url);
	}

	function ActivateLoadingAnimation()
	{
		$("loading").addClass("active");
	}

	function DeactivateLoadingAnimation()
	{
		$("loading").removeClass("active");
	}

	function TogglePassword($Icon)
	{
		const Input = $Icon.closest(".input-wrapper").find("input");

		if (Input.attr("type") === "password")
		{
			Input.attr("type", "text");
			$Icon.attr("icon", "mage:eye-off");
		}
		else
		{
			Input.attr("type", "password");
			$Icon.attr("icon", "mage:eye");
		}
	}

	function ToggleNavbar()
	{
		$("navbar.side").toggleClass("active");
		localStorage.setItem("sidebarClosed", !$("navbar.side").hasClass("active"));
	}

	function ToggleButtonList($ButtonList)
	{
		$ButtonList.toggleClass("listed");
		$ButtonList.next(".button-list-wrapper").toggleClass("active");
	}

	function RemoveElement($Element)
	{
		$Element.remove();
	}

	function RemoveDialog()
	{
		const $Dialog = $("dialog[open]");

		if ($Dialog.length)
			RemoveElement($Dialog[0]);
	}

	function AutoResizeTextarea($Element)
	{
		const MaxHeight = 300;
		const ScrollHeight = $Element[0].scrollHeight;

		$Element.height("auto");

		if (ScrollHeight <= MaxHeight)
			$Element.height($Element[0].scrollHeight);
		else
			$Element.height(MaxHeight);
	}

	function ValidateForms()
	{
		$("form").each(function()
		{
			const $Form = $(this);
			const initialValues = {};
			const $RequiredFields = $Form.find("[required]");
			const $NamedInputs = $Form.find("[name]");

			if ($RequiredFields.length === 0 || $NamedInputs.length === 0)
			{
				$Form.find("button").prop("disabled", false);

				return;
			}

			$NamedInputs.each(function()
			{
				const name = $(this).attr("name");

				if (!name)
					return;

				if ($(this).is(":checkbox"))
					initialValues[name] = $(this).prop("checked");
				else
					initialValues[name] = $(this).val();
			});

			function ValidateLocalForm()
			{
				let IsValid = true;
				let IsChanged = false;

				$RequiredFields.each(function()
				{
					if ($(this).is(":checkbox") && $(this).prop("required") && !$(this).prop("checked"))
					{
						IsValid = false;

						return false;
					}
					
					if (!this.checkValidity())
					{
						IsValid = false;

						return false;
					}
				});

				$NamedInputs.each(function()
				{
					const name = $(this).attr("name");

					if (!name || !initialValues.hasOwnProperty(name))
						return;

					if ($(this).is(":checkbox"))
					{
						if (initialValues[name] !== $(this).prop("checked"))
						{
							IsChanged = true;

							return false;
						}
					}
					else
					{
						if (initialValues[name] !== $(this).val())
						{
							IsChanged = true;

							return false;
						}
					}
				});

				const enable = IsValid && IsChanged;

				$Form.find("button.button, button.confirmation-ok").prop("disabled", !enable);
				$Form.find("button.confirmation-close").prop("disabled", false);
			}

			$Form.on("input change", "input[type=checkbox], select, textarea", ValidateLocalForm);
			$Form.on("input change", "input:not([type=checkbox])", ValidateLocalForm);
			$Form.find("select").each(function()
			{
				const $select = $(this);

				if ($select.hasClass("select2-hidden-accessible"))
				{
					$select.on("select2:select select2:unselect", function()
					{
						$select.trigger("change");
						ValidateLocalForm();
					});
				}
			});

			ValidateLocalForm();
		});
	}

	function DialogInputData($path = "{{ url()->current() }}", $icon = "", $title = "", $type = "POST", $innerContent = "")
	{
		const $content = 
		`<form action="${$path}" method="${$type}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="_method" value="${$type}">

			<div class="top">
				<img src="{{ \App\Http\Controllers\HelperController::Asset('assets/img/background-banner.png') }}" alt="background-banner">
				<div class="text">
					<iconify-icon icon="${$icon}" width="24"></iconify-icon>
					${$title}
					<x-button class="confirmation-close top" icon="mingcute:close-fill" iconwidth="25" />
				</div>
			</div>
			<div class="content">
				${$innerContent}
				<div class="buttons">
					<x-button class="confirmation-close">Batal</x-button>
					<x-button class="confirmation-ok active" disabled>Simpan</x-button>
				</div>
			</div>
		</form>`;

		CreateDialog($content, "input-data");
	}

	function DialogMessage($Type = 0, $DialogMessageText = ["Warning!", ""], $DialogMessageButtonText = ["Close", "OK"], $Functions = [null, null])
	{
		let $IconAnimation = "{{ \App\Http\Controllers\HelperController::Asset('assets/anim/warning.json') }}";
		let $ConfirmOK = "hidden";

		if ($Type == 0)	// Info
		{
			$IconAnimation = "{{ \App\Http\Controllers\HelperController::Asset('assets/anim/warning.json') }}";
			$ConfirmOK = "hidden";
		}
		else if ($Type == 1)	// Confirmation
		{
			$IconAnimation = "{{ \App\Http\Controllers\HelperController::Asset('assets/anim/warning.json') }}";
			$ConfirmOK = "active";
		}
		else if ($Type == 2)	// Success
		{
			$IconAnimation = "{{ \App\Http\Controllers\HelperController::Asset('assets/anim/success.json') }}";
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
		let $IconAnimation = "{{ \App\Http\Controllers\HelperController::Asset('assets/anim/warning.json') }}";

		if ($Type == 0)	// Info
		{
			$Class = "warning";
			$IconAnimation = "{{ \App\Http\Controllers\HelperController::Asset('assets/anim/warning.json') }}";
		}
		else if ($Type == 1)	// Success
		{
			$Class = "success";
			$IconAnimation = "{{ \App\Http\Controllers\HelperController::Asset('assets/anim/success.json') }}";
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

	function FormConfirmation($Event, $DialogMessageText = ["Confirmation", ""], $DialogMessageButtonText = ["Cancel", "OK"])
	{
		const Form = $Event.target;

		$Event.preventDefault();

		if (!Form.checkValidity())
		{
			Form.reportValidity();

			return false;
		}

		DialogMessage(1, $DialogMessageText, $DialogMessageButtonText, [ ,
		function()
		{
			Form.submit();
		}]);

		return false;
	}

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

	$(window).on("load", function()
	{
		DeactivateLoadingAnimation();
	});

	$(window).on("beforeunload", function()
	{
		RemoveDialog();
		ActivateLoadingAnimation();
	});

	$(window).on("pageshow", function(event)
	{
		if (event.originalEvent.persisted)
			DeactivateLoadingAnimation();
	});

	$(function()
	{
		var navbarSideClosed = localStorage.getItem("sidebarClosed") === "true";

		if (!navbarSideClosed)
			$("navbar.side").addClass("active");
		
		$("[autofocus]").each(function()
		{
			var $Input = $(this);
			$Input.focus();

			if (this.setSelectionRange && $Input.val().length)
			{
				var Len = $Input.val().length;
				this.setSelectionRange(Len, Len);
			}
		});

		ValidateForms();

		$("select.select2").select2();
	});

	$(document).on("input change focus", "textarea", function()
	{
		AutoResizeTextarea($(this));
	});

	$(document).on("input change", "input#useridnumber", function()
	{
		$(this).val($(this).val().toUpperCase());
	});

	$(document).on("input change", "dialog.input-data input#useridnumber", function()
	{
		$("input#password").val($(this).val());
	});

	$(document).on("submit", "form#form-logout", function(event)
	{
		return FormConfirmation(event, ["Anda Yakin Akan Mengakhiri Sesi?", "Sesi Anda Akan Berakhir Dan Silahkan Memulai Sesi Baru"], ["Batal", "Keluar"]);
	});

	$(document).on("submit", "form#form-delete", function(event)
	{
		return FormConfirmation(event, ["Anda Yakin Akan Menghapus Data Ini?", "Pastikan Data Yang Anda Pilih Benar"], ["Batal", "Hapus"]);
	});

	$(document).on("submit", "form#form-delete-user", function(event)
	{
		return FormConfirmation(event, ["Anda Yakin Akan Menghapus Data Pengguna Ini?", "Pastikan Data Pengguna Yang Anda Pilih Benar<br><br>Data Pengguna Yang Dihapus, Memungkinkan Juga Akan Menghapus Seluruh Data Yang Mencakup ID, Nama, Hak Akses, Dan Surat-Surat Yang Berhubungan Dengan Data Pengguna Serta Aksi Penghapusan Tidak Bisa Dibatalkan Atau Dikembalikan"], ["Batal", "Hapus"]);
	});

	$(document).on("submit", "form#form-delete-schedule", function(event)
	{
		return FormConfirmation(event, ["Anda Yakin Akan Menghapus Data Ini?", "Pastikan Data Yang Anda Pilih Benar<br><br>Data Akan Dihapus Secara Keseluruhan, Mencakup Data Akademik (Seminar Atau Sidang Akhir) Mahasiswa, Pengumuman Atau Undangan Akademik, Dan Jadwal Yang Anda Pilih Serta Aksi Penghapusan Tidak Bisa Dibatalkan Atau Dikembalikan"], ["Batal", "Hapus"]);
	});

	$(document).on("submit", "form#form-letter", function(event)
	{
		if (IsGoogleDriveUrl($("#link").val().trim()))
		{
			return FormConfirmation(event, ["Anda Yakin Akan Menyimpan Data Ini?", "Pastikan Data Yang Dimasukkan Benar"], ["Batal", "Simpan"]);
		}
		else
		{
			DialogMessage(0, ["Terjadi Kesalahan!", "Pastikan Link Yang Anda Masukkan Adalah Link GoogleDrive"], ["Kembali"]);

			return event.preventDefault();
		}
	});

	$(document).on("submit", "form#form-verification", function(event)
	{
		return FormConfirmation(event, ["Apakah Anda Yakin Verifikasi Data Ini?", "Pastikan Data Yang Anda Pilih Benar"], ["Batal", "Verifikasi"]);
	});

	$(document).on("submit", "form#form-rejection", function(event)
	{
		return FormConfirmation(event, ["Apakah Anda Yakin Tolak Data Ini?", "Pastikan Data Yang Anda Pilih Benar. Data Yang Ditolak Akan Hilang"], ["Batal", "Tolak"]);
	});

	$(document).on("input", "input#search", function()
	{
		return UpdateQueryParam("search", this.value, ["page"]);
	});

	$(document).on("change", "select#semester", function()
	{
		return UpdateQueryParam("semester", this.value, ["page"]);
	});

	$(document).on("change", "select#type", function()
	{
		return UpdateQueryParam("type", this.value, ["page"]);
	});

	$(document).on("click", "button.navigator-button", function()
	{
		return UpdateQueryParam("page", $(this).attr("data-link"));
	});

	$(document).on("click", "button#folder-link", function()
	{
		return DialogMessage(0, ["Dokumen Tidak Tersedia", "Silakan Kirim Dokumen Berupa Link Google Drive Di Menu <a href='" + $(this).attr("data-link") + "'>" + $(this).attr("data-text") + "</a>"], ["Kembali"]);
	});

	$(document).on("click", "button#folder-link-admin", function()
	{
		return DialogMessage(0, [$(this).attr("data-text-title"), $(this).attr("data-text-content")], ["Kembali"]);
	});

	$(document).on("keydown", "dialog form", function(event)
	{
		if (event.key === "Enter")
		{
			if (event.target.tagName.toLowerCase() !== "textarea")
				event.preventDefault();
		}
	});

	$(document).on("click", "dialog.input-data .button.confirmation-close", function()
	{
		RemoveDialog();
	});

	$(document).on("click", "dialog", function(event)
	{
		if (event.target === this)
			RemoveDialog();
	});
</script>