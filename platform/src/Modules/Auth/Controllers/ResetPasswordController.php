<?php
namespace Lumia\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Lumia\Http\Controllers\Controller;
use Lumia\Http\Controllers\Auth\Traits\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
     * |--------------------------------------------------------------------------
     * | Password Reset Controller
     * |--------------------------------------------------------------------------
     * |
     * | This controller is responsible for handling password reset requests
     * | and uses a simple trait to include this behavior. You're free to
     * | explore this trait and override any methods you wish to tweak.
     * |
     */
    
    use ResetsPasswords;

    /**
     * Get the response for a successful password reset.
     *
     * @param string $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse($response)
    {
        return $this->respondWithSuccess(Lang::get($response));
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param
     *            \Illuminate\Http\Request
     * @param string $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return $this->respondWithError(Lang::get($response));
    }
}
