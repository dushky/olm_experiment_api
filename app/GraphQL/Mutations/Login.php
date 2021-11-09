<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Config\Repository as Config;
use Laravel\Sanctum\Contracts\HasApiTokens;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use DanielDeWit\LighthouseSanctum\Exceptions\HasApiTokensException;
use DanielDeWit\LighthouseSanctum\Traits\CreatesUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Log;



class Login
{
    use CreatesUserProvider;

    protected AuthManager $authManager;
    protected Config $config;
    
    /**
     * Return a value for the field.
     *
     * @param  @param  null  $root Always null, since this field has no parent.
     * @param  array<string, mixed>  $args The field arguments passed by the client.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Shared between all fields.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Metadata for advanced query resolution.
     * @return mixed
     */
    public function __invoke($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $userProvider = $this->createUserProvider();

        // $user = $userProvider->retrieveByCredentials([
        //     'email'    => $args['email'],
        //     'password' => $args['password'],
        // ]);

        // if (! $user || ! $userProvider->validateCredentials($user, $args)) {
        //     throw new AuthenticationException('The provided credentials are incorrect.');
        // }

        // if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
        //     throw new AuthenticationException('Your email address is not verified.');
        // }

        // if (! $user instanceof HasApiTokens) {
        //     throw new HasApiTokensException($user);
        // }
        // config('sanctum.guard', 'web')
        $guard = Auth::guard('web');
        if(!$guard->attempt($args)) {
            return [
                'ERROR' => "ERROR"
            ];
            throw new \Error('Invalid credentials.');
        }
        $user = $guard->user();

        return [
            'SUCCESS' => $user,
        ];
    }

    public function __construct(AuthManager $authManager, Config $config) {
        $this->authManager = $authManager;
        $this->config = $config;
    }

    protected function getAuthManager(): AuthManager
    {
        return $this->authManager;
    }

    protected function getConfig(): Config
    {
        return $this->config;
    }
}
