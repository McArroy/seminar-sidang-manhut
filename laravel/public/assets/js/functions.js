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