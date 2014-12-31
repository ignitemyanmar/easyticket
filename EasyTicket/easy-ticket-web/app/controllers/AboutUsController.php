<?php
class AboutUsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$response=Advertisement::all();
		return View::make('aboutus.index');
	}
}
?>