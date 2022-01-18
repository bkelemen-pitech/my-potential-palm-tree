<?php

declare(strict_types=1);

namespace App\Security;

use App\Traits\ExceptionMessageTrait;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class LoginFormAuthenticator extends AbstractGuardAuthenticator
{
    use ExceptionMessageTrait;

    protected const LOGIN_ROUTE_NAME = 'app.v1.login';
    protected TokenStorageInterface $preAuthenticationTokenStorage;
    protected EventDispatcherInterface $dispatcher;
    protected PasswordEncoderInterface $passwordEncrypter;
    protected AuthTokenBuilder $authTokenBuilder;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        TokenStorageInterface $preAuthenticationTokenStorage,
        PasswordEncoderInterface $passwordEncrypter,
        AuthTokenBuilder $authTokenBuilder
    ) {
        $this->dispatcher = $dispatcher;
        $this->preAuthenticationTokenStorage = $preAuthenticationTokenStorage;
        $this->passwordEncrypter = $passwordEncrypter;
        $this->authTokenBuilder = $authTokenBuilder;
    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE_NAME === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $body = $request->toArray();
        foreach (['username', 'password'] as $key) {
            if (!array_key_exists($key, $body)) {
                throw new AuthenticationCredentialsNotFoundException($key);
            }

            if (empty($body[$key]) || !is_string($body[$key])) {
                throw new AuthenticationException($key);
            }
        }

        return [
            'username' => $body['username'],
            'password' => $body['password'],
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByIdentifier($credentials);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName)
    {
        return new JsonResponse(['token' => $token->getCredentials()], Response::HTTP_CREATED);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $authException)
    {
        $message = $this->buildExceptionMessage(Response::HTTP_UNAUTHORIZED, $authException->getMessageKey());
        $response = new JsonResponse($message->toArray(), Response::HTTP_UNAUTHORIZED);
        $event = new AuthenticationFailureEvent($authException, $response);
        $this->dispatcher->dispatch($event, Events::AUTHENTICATION_FAILURE);

        return $event->getResponse();
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required',
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

//    public function start(Request $request, AuthenticationException $authException = null): JsonResponse
//    {
//        $data = [
//            'message' => 'Authentication Required',
//        ];
//
//        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
//    }

    public function supportsRememberMe(): bool
    {
        return false;
    }

    public function createAuthenticatedToken(UserInterface $user, string $providerKey)
    {
        $authToken = $this->authTokenBuilder->createForUser($user, $providerKey);
        $this->dispatcher->dispatch(new JWTAuthenticatedEvent([], $authToken), Events::JWT_AUTHENTICATED);

        return $authToken;
    }
//    public function supports(Request $request): ?bool
//    {
//        return self::LOGIN_ROUTE_NAME === $request->attributes->get('_route')
//            && $request->isMethod('POST');
//    }
//
//    public function authenticate(Request $request): PassportInterface
//    {
//        var_dump('test');
//        $apiToken = $request->headers->get('X-AUTH-TOKEN');
//        if (null === $apiToken) {
//            // The token header was empty, authentication fails with HTTP Status
//            // Code 401 "Unauthorized"
//            throw new CustomUserMessageAuthenticationException('No API token provided');
//        }
//
//        return new SelfValidatingPassport(new UserBadge($apiToken));
//    }
//
//    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
//    {
//        var_dump('flure');
//
//        // on success, let the request continue
//        return null;
//    }
//
//    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
//    {
//        var_dump('flure');
//        $data = [
//            // you may want to customize or obfuscate the message first
//            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
//
//            // or to translate this message
//            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
//        ];
//
//        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
//    }
}
