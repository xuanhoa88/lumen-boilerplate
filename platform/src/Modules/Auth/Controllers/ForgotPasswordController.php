<?php
namespace Lumia\Http\Controllers\Auth;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Lumia\Http\Controllers\Controller;

class ForgotPasswordController extends Controller
{
    /*
     * |--------------------------------------------------------------------------
     * | Password Reset Controller
     * |--------------------------------------------------------------------------
     * |
     * | This controller is responsible for handling password reset emails and
     * | includes a trait which assists in sending these notifications from
     * | your application to your users. Feel free to explore this trait.
     * |
     */
    
    use SendsPasswordResetEmails;

    /**
     * Send a reset link to the given user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function forgot(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param string $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse($response)
    {
        return $this->respondWithSuccess(Lang::get($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param
     *            \Illuminate\Http\Request
     * @param string $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(ForgotPasswordRequest $request, $response)
    {
        return $this->respondWithError('If an account with this email exists, an email has been sent with instructions');
    }
}
