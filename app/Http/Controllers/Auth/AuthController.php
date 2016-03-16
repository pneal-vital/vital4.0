<?php namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use \Lang;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;

    //protected $redirectTo = '/home';
    //protected $loginPath = '/auth/login';
    protected $username = 'name';

    // (from: vendor/laravel/framework/src/Illuminate/Auth/Passwords/PasswordBroker.php)
    // Once we have the reset token, we are ready to send the message out to this
    // user with a link to reset their password. We will then redirect back to
    // the current URI having nothing set in the session to indicate errors.
    protected $tokens;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(TokenRepositoryInterface $tokens)
    {
        $this->tokens = $tokens;
        $this->middleware('guest', ['except' => ['getLogout','changePassword']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function changePassword()
    {
        $token = $this->tokens->create(\Auth::user());
        $this->getLogout();
        return redirect('/password/reset/'.$token)
            ->with('status', Lang::get('labels.status.ChangePassword'));
    }
}
