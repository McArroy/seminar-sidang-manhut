<?php

namespace App\Http\Controllers;

use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SeminarController extends Controller
{
	public function __construct()
	{
		if (!Auth::check())
			return redirect("/");
	}
	
	public function index()
	{
		$posts = Seminar::all();

		return view("posts.index", compact("posts"));
	}

	public function Create()
	{
		if (!Auth::check() || Auth::user()->userrole !== "student")
			return redirect("/");
		
		return view("student.registrationform");
	}

	public function Created(Request $request)
	{
		if (!Auth::check() || Auth::user()->userrole !== "student")
			return redirect("/");

		$data = session("validated_data_letter", []);

		return view("student.registrationletter", ["data" => $data]);
	}

	public function Store(Request $request)
	{
		if (!Auth::check() || Auth::user()->userrole !== "student")
			return redirect("/");

		$validated = $request->validate(
		[
			"useridnumber" => "required|string|max:33",
			"studyprogram" => "required|string|max:127",
			"department" => "required|string|max:127",
			"supervisor1" => "required|string|max:255",
			"supervisor2" => "required|string|max:255",
			"date" => "required|string|max:17",
			"time" => "required|string|max:17",
			"place" => "required|string|max:255",
			"title" => "required|string|max:255",
			"comment" => "nullable|string|max:1000",
			"status" => "nullable|integer|in:0,1"
		]);

		$validated["seminarid"] = (string)Str::uuid();

		Seminar::create($validated);

		session(["validated_data_letter" => $validated]);

		return redirect()->route("student.registrationletter")->with("toast", "Post created.");
	}

	public function show(Seminar $post)
	{
		return view("posts.show", compact("post"));
	}

	public function edit(Seminar $post)
	{
		return view("posts.edit", compact("post"));
	}

	public function update(Request $request, Post $post)
	{
		$request->validate(
		[
			"title" => "required|string|max:255",
			"content" => "required|string",
		]);

		$post->update($request->only("title", "content"));

		return redirect()->route("posts.index")->with("success", "Post updated.");
	}

	public function destroy(Post $post)
	{
		$post->delete();

		return redirect()->route("posts.index")->with("success", "Post deleted.");
	}
}