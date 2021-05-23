<?php

namespace NikitaMikhno\LaravelDiscourse\Contracts;

interface SingleSignOn
{
    public function setSecret($secret);

    public function validatePayload($payload, $signature);

    public function getNonce($payload);

    public function getReturnSSOURL($payload);

    public function getSignInString(
        $nonce,
        $id,
        $email,
        $extraParameters = []
    );
}
