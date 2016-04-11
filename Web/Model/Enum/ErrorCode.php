<?php

namespace FrontNginx\Web\Model\Enum;

abstract class ErrorCode
{
    const PasswordIncorrect = 1;
    const UserNotActivated = 2;
    const UserNotFound = 3;
    const UsernameTaken = 4;
    const UsernameNotFound = 5;
    const ActivationCodeIncorrect = 6;
}

?>