<?php

namespace System\Aplicacao\Permanencia;

use System\Entity\EntityUser;

interface UserPermanenceRepository
{
    /**
     * @param EntityUser $user
     * @return void
     */
    public function addPermanence(EntityUser $user): void;

    /**
     * @param EntityUser $user
     * @return mixed
     */
    public function checkConect(EntityUser $user): bool;

    /**
     * @param EntityUser $user
     * @return mixed
     */
    public function removePermancence(EntityUser $user): void;
}