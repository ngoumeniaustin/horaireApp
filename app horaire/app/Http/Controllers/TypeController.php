<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//		Type::factory(10)->create();
		$types = Type::all();

		return response(json_encode($types), 200);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{

		return view("typesform");
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{


		try {
			$name  = $request->name;

			$types = new Type([
				'name' => $name
			]);

			$types->save();
		} catch (Exception $e) {
			return response(false, 500);
		}

		return response(true, 200);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{

		try {
			$tp = Type::findOrFail($id);
			return response(json_encode($tp), 200);
		} catch (Exception $e) {
			return response(false, 500);
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit()
	{
		return view("typesEdit");
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Type  $type
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{


		try {
			$id = $request->id;
			$name = $request->name;

			$type = Type::where('id', $id)->update([
				'name' => $name
			]);

			$type = Type::findOrFail($id);


			return response(true, 200);
		} catch (Exception $e) {
			return response($id, 500);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Type  $type
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{



		try {
			$name = $request->name;
			$id = Type::where('name', $name)->first()->delete();

			return response(true, 200);
		} catch (Exception $e) {
			return response(false, 500);
		}
	}
}
