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

			if (name)
				initialValues[name] = $(this).val();
		});

		function ValidateLocalForm()
		{
			let IsValid = true;
			let IsChanged = false;

			$RequiredFields.each(function()
			{
				if (!this.checkValidity())
				{
					IsValid = false;

					return false;
				}
			});

			$NamedInputs.each(function()
			{
				const name = $(this).attr("name");
				const currentValue = $(this).val();

				if (initialValues.hasOwnProperty(name) && initialValues[name] !== currentValue)
				{
					IsChanged = true;

					return false;
				}
			});

			const enable = IsValid && IsChanged;

			$Form.find("button.button, button.confirmation-ok").prop("disabled", !enable);
			$Form.find("button.confirmation-close").prop("disabled", false);
		}

		$Form.find("input, select, textarea").on("input change", ValidateLocalForm);
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