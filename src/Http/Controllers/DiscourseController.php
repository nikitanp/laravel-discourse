<?php

/**
 * Class SsoController
 *
 * Controller to process the Discourse SSO request.  There is a good bit of logic in here that almost feels like too
 * much for a controller, but given that this is the only thing that this controller is doing, I am not going to break
 * it out into a service class.
 *
 * @package Spinen\Discourse\Controllers
 *
 */

namespace NikitaMikhno\LaravelDiscourse\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use NikitaMikhno\LaravelDiscourse\Contracts\ApiClient;
use NikitaMikhno\LaravelDiscourse\Contracts\SingleSignOn;

class DiscourseController extends Controller
{
    /**
     * Package configuration
     *
     * @var Collection
     */
    protected $config;

    /**
     * SSOHelper Instance
     *
     * @var SingleSignOn
     */
    protected $sso;

    /**
     * Authenticated user
     *
     * @var User
     */
    protected $user;

    /**
     * SsoController constructor.
     *
     * @param Config $config
     * @param SingleSignOn $sso
     */
    public function __construct(Config $config, SingleSignOn $sso)
    {
        $this->loadConfigs($config);
        $this->sso = $sso;
    }

    /**
     * Process the SSO login request from Discourse
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws 403
     */
    public function login(Request $request)
    {
        $this->user = $request->user();
        $access = $this->config->get('user')
            ->get('access', true);

        if (!is_null($access) && !$this->parseUserValue($access)) {
            abort(403);
        }

        if (!($this->sso->validatePayload($payload = $request->get('sso'), $request->get('sig')))) {
            abort(403);
        }

        $query = $this->sso->getSignInString(
            $this->sso->getNonce($payload),
            $this->parseUserValue($this->config->get('user')
                ->get('external_id')),
            $this->parseUserValue($this->config->get('user')
                ->get('email')),
            $this->buildExtraParameters()
        );

        return redirect(trim($this->config->get('url'), '/') . '/session/sso_login?' . $query);
    }

    /**
     * Process the SSO logout request from Discourse
     *
     * @param ApiClient $discourse
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function logout(ApiClient $discourse)
    {
        if (!isset(Auth::user()->email)) {
            throw new AuthorizationException('User is not logged in or email not provided!');
        }

        $username = $discourse->getUsernameByEmail(Auth::user()->email);

        $discourse->logoutUser($username);

        Auth::logout();

        return redirect($this->config->get('url'));
    }

    /**
     * Build out the extra parameters to send to Discourse
     *
     * @return array
     */
    protected function buildExtraParameters(): array
    {
        return $this->config->get('user')
            ->except(['access', 'email', 'external_id'])
            ->reject([$this, 'nullProperty'])
            ->map([$this, 'parseUserValue'])
            ->map([$this, 'castBooleansToString'])
            ->toArray();
    }

    /**
     * Make boolean's into string
     *
     * The Discourse SSO API does not accept 0 or 1 for false or true.  You must send
     * "false" or "true", so convert any boolean property to the string version.
     *
     * @param $property
     *
     * @return string
     */
    public function castBooleansToString($property): string
    {
        if (!is_bool($property)) {
            return $property;
        }

        return ($property) ? 'true' : 'false';
    }

    /**
     * Cache the configs on the object as a collection
     *
     * The 'user' property will be an array, so go ahead and convert it to a collection
     *
     * @param Config $config
     */
    protected function loadConfigs(Config $config): void
    {
        $this->config = collect($config->get('discourse'));

        $this->config->put('user', collect($this->config->get('user')));
    }


    /**
     * Check to see if property is null
     *
     * @param string $property
     * @return bool
     */
    public function nullProperty(string $property): bool
    {
        return is_null($property);
    }

    /**
     * Get the property from the user
     *
     * If a string is passed in, then get it from the user object, otherwise, return what was given
     *
     * @param string $property
     * @return mixed
     */
    public function parseUserValue(string $property)
    {
        if (!is_string($property)) {
            return $property;
        }

        return $this->user->{$property};
    }
}
