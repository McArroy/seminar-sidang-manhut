<?php

namespace App\Http\Controllers;

use App\Models\Academic;
use App\Traits\DeterministicEncryption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HelperController;

class AcademicController extends Controller
{
	use DeterministicEncryption;
	
	private string $userId;
	private string $userRole;
	private string $queryType;

	public function __construct(Request $request)
	{
		$this->userId = Auth::user()->useridnumber;
		$this->userRole = Auth::user()->userrole;

		$allowedTypes = ["seminar", "thesisdefense"];
		$type = $request->query("type", "seminar");
		$this->queryType = in_array($type, $allowedTypes) ? $type : "seminar";
	}

	private function Validate(Request $request, bool $isUpdate = false) : array
	{
		$rules =
		[
			"studyprogram" => "nullable|string|max:127",
			"department" => "nullable|string|max:127",
			"semester" => "nullable|string|max:33",
			"address" => "nullable|string|max:1000",
			"supervisor1" => "required|string|max:255",
			"supervisor2" => "nullable|string|max:255",
			"date" => "required|date",
			"time" => "required|string|max:17",
			"room" => "required|string|max:255",
			"title" => "required|string|max:255",
			"link" => "nullable|string|max:1000",
			"comment" => "nullable|string|max:1000",
			"is_accepted" => "nullable|integer|in:0,1",
			"is_completed" => "nullable|integer|in:0,1"
		];

		$validated = $request->validate($rules);

		if (!$isUpdate)
		{
			$validated["academicid"] = (string)Str::uuid();
			$validated["lecturers"] = [$validated["supervisor1"], $validated["supervisor2"] ?? ""];
			
			$validated = collect($validated)->except(["supervisor1", "supervisor2"])->toArray();
		}

		return $validated;
	}

	private static function CheckRoomAvailability(string $room, string $date, string $time) : bool
	{
		return !Academic::where("room", $room)->where("date", $date)->where("time", $time)->exists();
	}

	public static function GetAll(array $columns = ["*"])
	{
		return Academic::select($columns)->get();
	}

	public static function GetById(string $academicid)
	{
		return Academic::where("academicid", $academicid)->first();
	}

	public function Index()
	{
		if ($this->userRole === "admin")
		{
			$academic = self::GetAll(["academicid", "academictype", "useridnumber", "date", "title", "link", "comment", "is_accepted", "is_completed", "created_at"]);
		}
		else if ($this->userRole === "student")
		{
			$userId = $this->userId;

			$academic = self::GetAll()->filter(function($academic) use ($userId)
			{
				return $academic->useridnumber === $userId;
			});
		}

		return $academic;
	}

	public function Created(Request $request)
	{
		$data = session()->pull("validated_data_letter", []);

		if (empty($data))
			return redirect()->route("student.dashboard");

		return view("student.registrationletter", compact("data"));
	}

	public function RePreview(Request $request, string $academicid)
	{
		$academic = Academic::where("academicid", $academicid)->first();

		if (!$academic)
			return HelperController::Message("dialog_info", [__($this->queryType . ".failedtoload"), __($this->queryType . ".notfound")]);

		if ($academic->useridnumber !== $this->userId)
			return HelperController::Message("dialog_info", [__($this->queryType . ".failedtoload"), __($this->queryType . ".accessdenied")]);

		$academic = 
		[
			"academictype" => $academic->useridnumber,
			"useridnumber" => $academic->useridnumber,
			"studyprogram" => $academic->studyprogram,
			"department" => $academic->department,
			"semester" => $academic->semester,
			"address" => $academic->address,
			"lecturers" => $academic->lecturers,
			"date" => $academic->date,
			"time" => $academic->time,
			"room" => $academic->room,
			"title" => $academic->title
		];

		session(["validated_data_letter" => $academic]);

		return redirect()->route("student.registrationletter", ["type" => $this->queryType, "mod" => "preview"]);
	}

	public function Store(Request $request)
	{
		$validated = $this->Validate($request);

		$validated["academictype"] = strtolower(trim($this->queryType));
		$validated["useridnumber"] = strtolower(trim($this->userId));

		if (!$this->CheckRoomAvailability($validated["room"], $validated["date"], $validated["time"]))
			return HelperController::Message("dialog_info", [__($this->queryType . ".failedtocreate"), __($this->queryType . ".roomnotavailable")]);

		Academic::create($validated);

		session(["validated_data_letter" => $validated]);

		return HelperController::Message("dialog_success", [__($this->queryType . ".succeededtocreate"), __($this->queryType . ".needrequirements")], ["student.registrationletter", ["type" => $this->queryType]]);
	}

	private function Update(array $data, Academic $academic)
	{
		if (isset($data["is_accepted"]))
		{
			if (($academic->link === null || empty($academic->link)) && $data["is_accepted"] !== 0)
				return HelperController::Message("dialog_info", [__($academic->academictype . ".failedtoverification"), __($academic->academictype . ".requirementsnotadded")]);

			$academic->update(["is_accepted" => $data["is_accepted"]]);

			if ($data["is_accepted"] === 1)
				$academic->update(["comment" => ""]);
			
			return HelperController::Message("toast_success", $data["message"] ?? "");
		}
		else if (isset($data["comment"]))
		{
			$academic->update(["comment" => $data["comment"]]);
			
			return HelperController::Message("toast_success", $data["message"] ?? "");
		}
		else if (isset($data["link"]))
		{
			$academic->update(["link" => $data["link"]]);
			
			return HelperController::Message("toast_success", __($academic->academictype . ".requirementsadded"), "student.dashboard");
		}
	}

	public function UpdateLink(Request $request)
	{
		$data = $request->validate(
		[
			"link" => "required|string|max:1000"
		]);

		$userId = $this->encryptDeterministic($this->userId);

		$academicToUpdate = Academic::where("useridnumber", $userId)->where("academictype", $this->queryType)->whereNull("link")->orderBy("created_at")->first();

		if (!$academicToUpdate)
			return HelperController::Message("toast_info", __($this->queryType . ".completed"));

		return $this->Update($data, $academicToUpdate);
	}

	public static function GetDataTime(string $queryType = "seminar") : array
	{
		if ($queryType === "seminar")
			$dataTime =
			[
				"07:00 - 08:00",
				"07:30 - 08:30",
				"08:00 - 09:00",
				"08:30 - 09:30",
				"09:00 - 10:00",
				"09:30 - 10:30",
				"10:00 - 11:00",
				"10:30 - 11:30",
				"11:00 - 12:00",
				"11:30 - 12:30",
				"12:00 - 13:00",
				"12:30 - 13:30",
				"13:00 - 14:00",
				"13:30 - 14:30",
				"14:00 - 15:00",
				"14:30 - 15:30",
				"15:00 - 16:00",
				"15:30 - 16:30",
				"16:00 - 17:00"
			];
		else if ($queryType === "thesisdefense")
			$dataTime =
			[
			"07:00 - 09:00",
			"07:30 - 09:30",
			"08:00 - 10:00",
			"08:30 - 10:30",
			"09:00 - 11:00",
			"09:30 - 11:30",
			"10:00 - 12:00",
			"10:30 - 12:30",
			"11:00 - 13:00",
			"11:30 - 13:30",
			"12:00 - 14:00",
			"12:30 - 14:30",
			"13:00 - 15:00",
			"13:30 - 15:30",
			"14:00 - 16:00",
			"14:30 - 16:30",
			"15:00 - 17:00",
			"15:30 - 17:30",
			"16:00 - 18:00"
		];

		return $dataTime;
	}

	public static function GetCommentById(string $academicid)
	{
		$comment = Academic::findOrFail($academicid)->comment;

		return response()->json(["comment" => $comment]);
	}

	public function Accept(Request $request, string $academicid)
	{
		$academic = Academic::where("academicid", $academicid)->first();

		if (!$academic)
			return HelperController::Message("dialog_info", [__($this->queryType . ".failedtoverification"), __($this->queryType . ".notfound")]);

		$data = $request->merge(
		[
			"is_accepted" => 1,
			"message" => __($this->queryType . ".succeededtoaccept")
		])->all();

		return $this->Update($data, $academic);
	}

	public function Comment(Request $request, string $academicid)
	{
		$academic = Academic::where("academicid", $academicid)->first();

		if (!$academic)
			return HelperController::Message("dialog_info", [__($this->queryType . ".failedtocomment"), __($this->queryType . ".notfound")]);

		$data = $request->validate(
		[
			"comment" => "required|string|max:1000"
		]);

		return $this->Update($data, $academic);
	}

	public function Reject(Request $request, string $academicid)
	{
		$academic = Academic::where("academicid", $academicid)->first();

		if (!$academic)
			return HelperController::Message("dialog_info", [__($this->queryType . ".failedtoverification"), __($this->queryType . ".notfound")]);

		$data = $request->merge(
		[
			"is_accepted" => 0,
			"message" => __($this->queryType . ".succeededtoreject")
		])->all();

		return $this->Update($data, $academic);
	}

	public function Destroy(Request $request, string $academicid)
	{
		$academic = Academic::where("academicid", $academicid)->first();

		if (!$academic)
			return HelperController::Message("dialog_info", [__($this->queryType . ".failedtodelete"), __($this->queryType . ".notfound")]);

		if ($academic->is_accepted === 1)
			return HelperController::Message("dialog_info", [__($this->queryType . ".failedtodelete"), __($this->queryType . ".failedtodeletealreadyaccepted")]);

		$academic->delete();

		return HelperController::Message("toast_success", __($this->queryType . ".succeededtodelete"));
	}
}