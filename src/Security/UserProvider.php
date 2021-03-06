<?php

namespace App\Security;

use App\Enum\BepremsEnum;
use App\Enum\UserEnum;
use Kyc\InternalApiBundle\Exception\ResourceNotFoundException;
use Kyc\InternalApiBundle\Service\UserService;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserProvider implements UserProviderInterface
{
    const ROLE_PREFIX = 'ROLE_';
    protected UserService $userService;
    protected PasswordEncoderInterface $passwordEncrypter;
    protected SerializerInterface $serializer;

    public function __construct(
        UserService $userService,
        PasswordEncoderInterface $passwordEncrypter,
        SerializerInterface $serializer
    ) {
        $this->userService = $userService;
        $this->passwordEncrypter = $passwordEncrypter;
        $this->serializer = $serializer;
    }

    public function loadUserByIdentifier($identifier): UserInterface
    {
        try {
            $loginData = (array) $identifier;
            $loginData[UserEnum::PASSWORD] = $this->passwordEncrypter->encodePassword($loginData[UserEnum::PASSWORD], null);
            $loginData[BepremsEnum::APPLICATION] = BepremsEnum::LOGIN_APPLICATION;

            $userData = $this->userService->loginUser($loginData);
            $user = User::createFromPayload(
                $userData->getLogin(),
                [
                    UserEnum::PASSWORD => $userData->getPassword(),
                    UserEnum::USER_ROLES => [self::ROLE_PREFIX . $userData->getRole()],
                    UserEnum::USER_ID => $userData->getUserId(),
                ]
            );
        } catch (ResourceNotFoundException $exception) {
            throw new BadCredentialsException();
        }

        return $user;
    }

    public function loadUserByUsername($username): UserInterface
    {
        return $this->loadUserByIdentifier($username);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }
}
