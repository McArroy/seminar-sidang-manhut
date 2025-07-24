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