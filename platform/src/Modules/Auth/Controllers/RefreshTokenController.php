<?php
namespace Lumia\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lumia\Http\Controllers\Controller;

class RefreshTokenController extends Controller
{

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $guard = $this->guard();
        return $this->respondWithArray([
            'access_token' => $guard->refresh(),
            'token_type' => 'bearer',
            'expires_in' => $guard->factory()
                ->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
