<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Http\Controllers\DateIndoFormatterController;
use App\Http\Controllers\SeminarController;
use App\Http\Controllers\ThesisdefenseController;
use App\Http\Controllers\UserController;

use Carbon\Carbon;

class PageController extends Controller
{
	private array $semesterList = [];

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
						? "Ganjil {$semesterYear}/" . ($semesterYear + 1)
						: "Genap " . ($semesterYear - 1) . "/{$semesterYear}";
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
		if (!Auth::check() || Auth::user()->userrole !== "student")
			return redirect("/");

		$dataSeminar = app()->make(SeminarController::class)->Index()->map(function($item)
		{
			$item->submission_type = "Seminar";
			return $item;
		});

		$dataThesisdefense = app()->make(ThesisdefenseController::class)->Index()->map(function($item)
		{
			$item->submission_type = "Sidang Akhir";
			return $item;
		});

		$dataSubmissions = $dataSeminar->merge($dataThesisdefense)->sortByDesc("created_at")->values();

		return view("student.dashboard", compact("dataSubmissions"));
	}

	public function Schedule(Request $request)
	{
		if (!Auth::check())
			return redirect("/");

		$dataSeminar = app()->make(SeminarController::class)->GetAll()->map(function($item)
		{
			$item->submission_type = "Seminar";
			return $item;
		});

		$dataThesisdefense = app()->make(ThesisdefenseController::class)->GetAll()->map(function($item)
		{
			$item->submission_type = "Sidang Akhir";
			return $item;
		});

		$allData = $dataSeminar->merge($dataThesisdefense)->sortByDesc("created_at")->values();

		// filters
		$search = mb_strtolower(trim($request->query("search")));
		$type = $request->query("type");
		$semester = mb_strtolower(trim($request->query("semester")));
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
					UserController::GetUsername($item->useridnumber) ?? "",
					explode("-", $item->supervisor1 ?? "")[0],
					explode("-", $item->supervisor2 ?? "")[0],
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

		$page = (int)$request->query("page", 1);
		$perPage = 8;
		$total = $dataSubmissions->count();
		$results = $dataSubmissions->slice(($page - 1) * $perPage, $perPage)->values();

		$dataSubmissions = new LengthAwarePaginator($results, $total, $perPage, $page,
		[
			"path" => $request->url(),
			"query" => $request->query()
		]);

		return view("student.schedule", ["dataSubmissions" => $dataSubmissions, "semesterList" => $this->semesterList]);
	}
}