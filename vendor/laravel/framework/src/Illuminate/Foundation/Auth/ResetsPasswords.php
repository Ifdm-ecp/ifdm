<?php

namespace Illuminate\Foundation\Auth;

use App\password_resets;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ResetsPasswords
{
    use RedirectsUsers;

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEmail()
    {
        return view('auth.password');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
        $correo = $request->email;

        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
            \Session::flash('notificacion__', $this->sendNotify('success','A link to your email has been sent.'));
            return redirect()->back()->with('status', trans($response));
            case Password::INVALID_USER:
            \Session::flash('notificacion__', $this->sendNotify('danger','A link to your email has not been sent.'));
            return redirect()->back()->withErrors(['email' => trans($response)]);
        }
    }

    /*
     * se encarga de recibir el tipo y el mensaje de notificación, retorna la notificacion con formato.
     */
    public function sendNotify($tipo,$mensaje)
    {
        $alerta = '<div class="alert alert-'.$tipo.'">';
        $alerta .= "<center>$mensaje</center>";
        $alerta .= '</div>';

        return $alerta;
    }

    /**
     * Get the e-mail subject line to be used for the reset link email.
     *
     * @return string
     */
    protected function getEmailSubject()
    {
        return property_exists($this, 'subject') ? $this->subject : 'Your Password Reset Link';
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function getReset($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        $user_res = password_resets::where('token',$token)->first();
        if (is_null($user_res)) {
            \Session::set('notificacion__', $this->sendNotify('danger','The token does not exist or has expired.'));
            return redirect(url('login'));
        }

        $email = $user_res->email;

        return view('auth.reset')->with(['token' => $token,'email' => $email]);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postReset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
            \Session::set('notificacion__', $this->sendNotify('success','Your password has been changed.'));
            return redirect(url('login'));
            default:
            \Session::flash('notificacion__', $this->sendNotify('danger','Your password has not been changed.'));
            return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
        }
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);

        $user->save();

        Auth::login($user);
    }
}
