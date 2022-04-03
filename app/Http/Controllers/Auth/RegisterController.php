<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Events\UserLog;
use App\Events\UserRegistered;
use Illuminate\Http\Request;
use JWT\Authentication\JWT;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Events\OperationLog;
use League\OAuth2\Client\Provider\GenericProvider as OAuth2Provider;

use OAuthProvider;

define('AUTH_ERROR_MESSAGE', 'Authentication failed. Please try again.');

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * AAF callback
     */
    public function auth(Request $request)
    {
        if (env('OKTA_ISSUER')) {
            return $this->okta($request);
        }
        elseif (env('AAF_LINK')) {
            return $this->aaf($request);
        }
        return redirect()->intended('/login');
    }

    /**
     * AAF auth
     */
    public function aaf(Request $request)
    {
        $url = env('AAF_LINK');
        return redirect()->to($url);
    }

    /**
     * AAF callback
     */
    public function awt(Request $request)
    {
        $jwt = JWT::decode($request->assertion, env('AAF_SECRET', ''));
        $now = time();
        switch (true) {
            case $jwt->iss !== 'https://rapid.aaf.edu.au':
            case $jwt->aud !== env('AAF_AUD', ''):
            case $jwt->nbf > $now:
            case $jwt->exp <= $now:
            #TODO: case jtiExists($jwt->jti):
                return redirect()->to('/')->withErrors([AUTH_ERROR_MESSAGE]);
        }

        $attr = $jwt->{'https://aaf.edu.au/attributes'};
        $name = $attr->displayname;
        $email = $attr->mail;
        $role = Str::is('staff@*', $attr->edupersonscopedaffiliation) ? 'staff' : 'user';

        if (!$this->authenticate($name, $email)) {
            $this->create(['name' => $name, 'email' => $email, 'role' => $role]);
        }

        return redirect()->intended('/');
    }

    /**
     * LTI callback
     */
    public function lti(Request $request)
    {
        try {
            $provider = new OAuthProvider();
            $provider->consumerHandler(function($p) {
                if ($p->consumer_key !== env('LTI_KEY', '')) {
                    return OAUTH_CONSUMER_KEY_UNKNOWN;
                }
                $p->consumer_secret = env('LTI_SECRET', '');
                return OAUTH_OK;
            });
            $provider->timestampNonceHandler(function($p) {
                $timeDiff = time() - intval($p->timestamp);
                if ($timeDiff > 60 || $timeDiff < 0) {
                    return OAUTH_BAD_TIMESTAMP;
                }
                return OAUTH_OK;
            });
            $provider->isRequestTokenEndpoint(true);
            $provider->checkOAuthRequest();
        } catch (OAuthException $e) {
            switch ($e->getCode()) {
                case OAUTH_BAD_NONCE:
                    $error = 'This LTI request has expired. Please return to your application and restart the launch process.';
                    break;
                case OAUTH_BAD_TIMESTAMP:
                    $error = 'This request is too old. Please return to your application and restart the launch process.';
                    break;
                case OAUTH_CONSUMER_KEY_UNKNOWN:
                    $error = 'Consumer key is unknown, or has been temporarily disabled. Please check your consumer key settings and restart the launch process.';
                    break;
                case OAUTH_CONSUMER_KEY_REFUSED:
                    $error = 'The consumer key was refused. Please check your configuration and follow up with the LTI provider for support.';
                    break;
                case OAUTH_INVALID_SIGNATURE:
                    $error = 'The request signature is invalid, or does not match the signature computed.';
                    break;
                case OAUTH_PARAMETER_ABSENT:
                    $error = 'A required launch parameter was not provided.';
                    break;
                case OAUTH_SIGNATURE_METHOD_REJECTED:
                    $error = 'The signature method was not accepted by the service provider.';
                    break;
                default:
                    // We really shouldn't get any of the other OAuthProvider error codes.
                    $error = 'General launch error. Please follow up with the tool provider to consult any logs to further diagnose the issue.';
                    break;
            }
            return redirect()->to('/')->withErrors([$error]);
        }

        $name = $request->lis_person_name_full;
        $email = $request->lis_person_contact_email_primary;
        $role = 'user';

        if (!$this->authenticate($name, $email)) {
            $this->create(['name' => $name, 'email' => $email, 'role' => $role]);
        }

        return redirect()->intended('/');
    }

    /**
     * Okta callback
     */
    public function okta(Request $request)
    {
        $issuer = 'https://uts-preprod.okta.com/oauth2/default';
        $provider = new OAuth2Provider([
            'clientId' => env('OKTA_CLIENT_ID'),
            'clientSecret' => env('OKTA_CLIENT_SECRET'),
            'redirectUri' => env('APP_URL') . '/auth/okta',
            'urlAuthorize' => env('OKTA_ISSUER') . '/v1/authorize',
            'urlAccessToken' => env('OKTA_ISSUER') . '/v1/token',
            'urlResourceOwnerDetails' => env('OKTA_ISSUER') . '/v1/userinfo',
            'scopeSeparator' => ' ',
            'scopes' => ['openid', 'profile', 'email']
        ]);

        if (!isset($request->code) && !isset($request->state)) {
            $authorizationUrl = $provider->getAuthorizationUrl();
            $request->session()->put('oauth2state', $provider->getState());
            return redirect()->to($authorizationUrl);
        }

        $state = $request->session()->get('oauth2state');

        if (!$state || !$request->state || $state !== $request->state) {
            return redirect()->to('/')->withErrors([AUTH_ERROR_MESSAGE]);
        }

        if ($request->error) {
            $error = $request->error_description ? $request->error_description : AUTH_ERROR_MESSAGE;
            return redirect()->to('/')->withErrors([$error]);
        }

        try {
            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $request->code,
            ]);
            $attrs = $provider->getResourceOwner($accessToken)->toArray();

            $name = $attrs['name'];
            $email = $attrs['email'];

            if (!$this->authenticate($name, $email)) {
                $role = Str::is('*@uts.edu.au', $email) ? 'staff' : 'user';
                $this->create(['name' => $name, 'email' => $email, 'role' => $role]);
            }
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            return redirect()->to('/')->withErrors([$e->getMessage()]);
        } catch (Exception $e) {
            return redirect()->to('/')->withErrors([$e->getMessage()]);
        }
        return redirect()->intended('/');
    }

    /**
     * Attempt authentication with provided email and
     * update name if it has changed since last login
     *
     * @param string $name
     * @param string $email
     *
     * @return bool Success
     */
    protected function authenticate($name, $email)
    {
        $credentials = array(
            'email' => $email,
            'password' => ' ',
        );

        if (!Auth::attempt($credentials)) {
            return false;
        }

        $user = Auth::user();

        // update name if it's defferent to Canvas/
        if ($user->name !== $name) {
            $user->name = $name;
            $user->save();
        }

        $message = "login";
        event(new OperationLog($user, $message)); // used for updating log file compared to the db entry
        event(new UserLog($user, $message));

        return true;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param string $name
     * @param string $email
     * @param string $role
     *
     * @return \App\User
     */
    protected function create($params)
    {
        $name = isset($params['name']) ? $params['name'] : null;
        $email = isset($params['email']) ? $params['email'] : null;
        $password = isset($params['password']) ? $params['password'] : ' ';
        $role = isset($params['role']) ? $params['role'] : 'user';

        if (empty($name) || empty($email)) {
            return false;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        Auth::login($user);

        $message = "New user created";
        $msg = 'login';
        event(new OperationLog($user, $message));
        event(new UserRegistered($user, $role));
        event(new UserLog($user, $msg)); // logs activity

        return $user;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:10', 'strong_password', 'confirmed'],
        ]);
    }

}
