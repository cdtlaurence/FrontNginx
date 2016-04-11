<?php

namespace FrontNginx\Web\Model\Enum;

abstract class AccountStatus
{
    const Unconfirmed = 1;
    const Confirmed = 2;
    const ReadyToPost = 3;
    const Deactivated = 4;
}

?>