<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Http\Controllers\HelperController;
use App\Http\Controllers\DateIndoFormatterController;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoomController;

use Carbon\Carbon;

class PageController extends Controller
{
	private string $userRole;
	private array $semesterList = [];
	private string $queryType;

	public function __construct(Request $request)
	{
		$this->userRole = Auth::user()->userrole;
		$this->queryType = $request->query("type", "seminar");
	}

    private function PagePaginator(Request $request, Collection $data) : LengthAwarePaginator
	{
		$page = (int)$request->query("page", 1);
		$perPage = 8;
		$total = $data->count();
		$results = $data->slice(($page - 1) * $perPage, $perPage)->values();

		return $data = new LengthAwarePaginator($results, $total, $perPage, $page,
		[
			"path" => $request->url(),
			"query" => $request->query()
		]);
	}

	private function GetAllSemesterList(Collection $data) : array
	{
		$semesters = [];

		foreach($data as $item)
		{
			if (empty($item->date))
				continue;

			$semesterDate = Carbon::parse($item->date);
			$semesterMonth = $semesterDate->month;
			$semesterYear = $semesterDate->year;
			$semesterCode = ($semesterMonth >= 7 && $semesterMonth <= 12)
						? "{$semesterYear}-" . ($semesterYear + 1)
						: ($semesterYear - 1) . "-{$semesterYear}";
			$semesterText = ($semesterMonth >= 7 && $semesterMonth <= 12)
						? __("common.odd.text") . " {$semesterYear}/" . ($semesterYear + 1)
						: __("common.even.text") . " {($semesterYear - 1)}" . "/{$semesterYear}";
			$semesters[$semesterCode] = $semesterText;
		}

		$this->semesterList = collect($semesters)->sortKeysUsing(function($a, $b)
							{
								$yearA = (int) explode("-", $a)[0];
								$yearB = (int) explode("-", $b)[0];

								return $yearA <=> $yearB;
							})->toArray();

		return $this->semesterList;
	}

	public function Dashboard()
	{
		$academics = app()->make(AcademicController::class)->Index()->map(function($item)
		{
			$item->created_at_parsed = DateIndoFormatterController::Simple($item->created_at);
			return $item;
		})->sortByDesc("created_at")->values();

		if ($this->userRole === "admin")
		{
			$monthlyCounts = $academics->groupBy(function($item)
			{
				return Carbon::parse($item->date)->format("F");
			})->map(function($group)
			{
				return $group->count();
			});

			$months =
			[
				"January", "February", "March", "April", "May", "June",
				"July", "August", "September", "October", "November", "December"
			];

			$dataMonthLabels = $months;
			$dataMonthly = [];

			foreach ($months as $month)
			{
				$dataMonthly[] = $monthlyCounts->get($month, 0);
			}

			$data = compact("academics", "dataMonthLabels", "dataMonthly");
		}
		else if ($this->userRole === "student")
		{
			$data = compact("academics");
		}

		return view($this->userRole . ".dashboard", $data);
	}

	private function Users(Request $request, string $findAs = "students")
	{
		if ($findAs === "admins")
			$dataUsers = app()->make(UserController::class)->GetAdmins()->sortByDesc("created_at")->values();
		else if ($findAs === "students")
			$dataUsers = app()->make(UserController::class)->GetStudents()->sortByDesc("created_at")->values();
		else if ($findAs === "lecturers")
			$dataUsers = app()->make(UserController::class)->GetLecturers()->sortByDesc("created_at")->values();

		// filters
		$searchValidated = request()->validate(
		[
			"search" => "nullable|string|max:255"
		]);

		$search = isset($searchValidated["search"]) ? mb_strtolower(trim($searchValidated["search"])) : null;

		if ($search)
		{
			$dataUsers = $dataUsers->filter(function($item) use ($search)
			{
				$fields =
				[
					$item->username ?? "",
					$item->useridnumber ?? "",
					$item->userrole ?? ""
				];

				foreach ($fields as $field)
				{
					if (!is_string($field))
						continue;

					if (str_contains(mb_strtolower(trim($field)), $search))
						return true;
				}

				return false;
			})->values();
		}
		
		$dataUsers = $this->PagePaginator($request, $dataUsers);

		return $dataUsers;
	}

	public function Admins(Request $request)
	{
		$dataUsers = $this->Users($request, "admins");
		$currentUser = Auth::user();

		if ($this->userRole === "admin")
			return view("admin.admins", compact("dataUsers", "currentUser"));
	}

	public function Students(Request $request)
	{
		$dataUsers = $this->Users($request, "students");

		if ($this->userRole === "admin")
			return view("admin.students", compact("dataUsers"));
	}

	public function Lecturers(Request $request)
	{
		$dataUsers = $this->Users($request, "lecturers");

		if ($this->userRole === "admin")
			return view("admin.lecturers", compact("dataUsers"));
	}

	public function Rooms(Request $request)
	{
		$dataRooms = app()->make(RoomController::class)->Index($request);

		// filters
		$searchValidated = request()->validate(
		[
			"search" => "nullable|string|max:255"
		]);

		$search = isset($searchValidated["search"]) ? mb_strtolower(trim($searchValidated["search"])) : null;

		if ($search)
		{
			$dataRooms = $dataRooms->filter(function($item) use ($search)
			{
				$fields =
				[
					$item->roomname ?? ""
				];

				foreach ($fields as $field)
				{
					if (!is_string($field))
						continue;

					if (str_contains(mb_strtolower(trim($field)), $search))
						return true;
				}

				return false;
			})->values();
		}
		
		$dataRooms = $this->PagePaginator($request, $dataRooms);

		if ($this->userRole === "admin")
			return view("admin.rooms", compact("dataRooms"));
	}

	public function Academics(Request $request)
	{
		$academics = app()->make(AcademicController::class)->Index()->filter(function($item)
		{
			return ($item->academictype === $this->queryType) && ($item->is_accepted === null || $item->is_accepted === "");
		})->map(function($item)
		{
			$item->username = UserController::GetUsername($item->useridnumber);
			return $item;
		})->sortByDesc("created_at")->values();

		// filters
		$searchValidated = request()->validate(
		[
			"search" => "nullable|string|max:255"
		]);

		$search = isset($searchValidated["search"]) ? mb_strtolower(trim($searchValidated["search"])) : null;

		if ($search)
		{
			$academics = $academics->filter(function($item) use ($search)
			{
				$fields =
				[
					$item->useridnumber ?? "",
					UserController::GetUsername($item->useridnumber) ?? "",
					$item->title ?? ""
				];

				foreach ($fields as $field)
				{
					if (!is_string($field))
						continue;

					if (str_contains(mb_strtolower(trim($field)), $search))
						return true;
				}

				return false;
			})->values();
		}
		
		$academics = $this->PagePaginator($request, $academics);

		return view("admin.academics", compact("academics"));
	}

	public function Announcements(Request $request)
	{
		$academics = app()->make(AcademicController::class)->Index()->filter(function($item)
		{
			return ($item->academictype === $this->queryType) && ($item->is_accepted === 1);
		})->map(function($item)
		{
			$item->printable = LetterController::IsExist($item->academicid);
			$item->username = UserController::GetUsername($item->useridnumber);
			return $item;
		})->sortByDesc(function($item)
		{
			return [$item->is_completed === 0 ? 1 : 0, $item->created_at];
		})->values();

		// filters
		$searchValidated = request()->validate(
		[
			"search" => "nullable|string|max:255"
		]);

		$search = isset($searchValidated["search"]) ? mb_strtolower(trim($searchValidated["search"])) : null;

		if ($search)
		{
			$academics = $academics->filter(function($item) use ($search)
			{
				$fields =
				[
					$item->useridnumber ?? "",
					$item->username ?? "",
					$item->title ?? ""
				];

				foreach ($fields as $field)
				{
					if (!is_string($field))
						continue;

					if (str_contains(mb_strtolower(trim($field)), $search))
						return true;
				}

				return false;
			})->values();
		}

		$academics = $this->PagePaginator($request, $academics);
		$lecturers = app()->make(UserController::class)->GetLecturers()->pluck("username", "useridnumber")->mapWithKeys(fn($v, $k) => [$k . " - " . $v => $v])->toArray();

		return view("admin.announcements", compact("academics", "lecturers"));
	}

	public function Schedule(Request $request)
	{
		$dataSeminar = app()->make(SeminarController::class)->GetAll()->filter(function($item)
		{
			return $item->status === 1;
		})->map(function($item)
		{
			$item->submission_type = "Seminar";
			$item->username = UserController::GetUsername($item->useridnumber);
			$item->supervisor1 = explode(" - ", $item->supervisor1)[1];
			$item->supervisor2 = explode(" - ", $item->supervisor2)[1];
			$item->date_parsed = DateIndoFormatterController::Full($item->date, 1);
			return $item;
		});

		$dataThesisdefense = app()->make(ThesisdefenseController::class)->GetAll()->filter(function($item)
		{
			return $item->status === 1;
		})->map(function($item)
		{
			$item->submission_type = "Sidang Akhir";
			$item->username = UserController::GetUsername($item->useridnumber);
			$item->supervisor1 = explode(" - ", $item->supervisor1)[1];
			$item->supervisor2 = explode(" - ", $item->supervisor2)[1];
			$item->date_parsed = DateIndoFormatterController::Full($item->date, 1);
			return $item;
		});

		$allData = $dataSeminar->merge($dataThesisdefense)->sortByDesc("created_at")->values();

		// filters
		$validated = request()->validate(
		[
			"search" => "nullable|string|max:255",
			"type" => "nullable|string|max:129",
			"semester" => "nullable|string|max:255"
		]);

		$search = isset($validated["search"]) ? mb_strtolower(trim($validated["search"])) : null;
		$type = isset($validated["type"]) ? mb_strtolower(trim($validated["type"])) : null;
		$semester = isset($validated["semester"]) ? mb_strtolower(trim($validated["semester"])) : null;
		$this->GetAllSemesterList($allData);

		if ($type)
		{
			if ($type === "seminar")
				$dataSubmissions = $dataSeminar->sortByDesc("created_at")->values();
			else if ($type === "thesisdefense")
				$dataSubmissions = $dataThesisdefense->sortByDesc("created_at")->values();
			else
				$dataSubmissions = $allData;
		}
		else
		{
			$dataSubmissions = $allData;
		}

		if ($semester && $semester !== "all")
		{
			$dataSubmissions = $dataSubmissions->filter(function($item) use ($semester)
			{
				if (empty($item->date))
					return false;

				$date = Carbon::parse($item->date);
				$month = $date->month;
				$year = $date->year;

				$itemSemester = ($month >= 7 && $month <= 12)
							? "{$year}-" . ($year + 1)
							: ($year - 1) . "-{$year}";

				return $itemSemester === $semester;
			})->values();
		}

		if ($search)
		{
			$dataSubmissions = $dataSubmissions->filter(function($item) use ($search)
			{
				$fields =
				[
					$item->submission_type ?? "",
					$item->title ?? "",
					$item->useridnumber ?? "",
					$item->username ?? "",
					$item->supervisor1 ?? "",
					$item->supervisor2 ?? "",
					$item->place ?? "",
					$item->time ?? "",
					DateIndoFormatterController::Full($item->date, 1) ?? ""
				];

				foreach ($fields as $field)
				{
					if (!is_string($field))
						continue;

					if (str_contains(mb_strtolower(trim($field)), $search))
						return true;
				}

				return false;
			})->values();
		}

		if ($this->userRole === "admin")
			$dataSubmissions = HelperController::MarkIfDatePassed($dataSubmissions);
		else
			$dataSubmissions = HelperController::FilterByDateRange($dataSubmissions);

		$dataSubmissions = $this->PagePaginator($request, $dataSubmissions);

		return view("schedule", ["userRole" => $this->userRole, "dataSubmissions" => $dataSubmissions, "semesterList" => $this->semesterList]);
	}
}