<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;

class MeController
{
	public function __invoke(Request $request)
	{
		$uid = (int) $request->attributes->get('jwt_user_id');
		$user = get_user_by('ID', $uid);

		return response()->json([
			'id' => $uid,
			'email' => $user?->user_email,
			'name' => $user?->display_name,
		]);
	}

}