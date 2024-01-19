<?php

namespace System\Aplication\Request;

use stdClass;
use System\Aplication\Permancence\UserPermancenceRepository;
use System\Entity\EntityUser;


class Request 
{
    /**@var array */
    private $get;
    /**@var array */
    private $post;
    /**@var array */
    private $session;
    /**@var array  UserPermanenceRepository*/
    private $entityuser;


    public function __construct(array $get = [], array $post = [], array $session = [] )
    {
        $this->setGet($get);
        $this->setPost($post);
        $this->setSession($session);
    }

    public function setDefaults(): void
    {
        $this->setGet($_GET);
        $this->setPost($_POST);
        $this->setSession($_SESSION ?? []);
    }

    public function get(string $name = null)
    {
        if(!$name) {
            return $this->get;
        }
        return $this->get->{$name} ?? null;
    }

    public function post(string $name = null) 
    {
        if(!$name){
            return $this->post();
        }
        return $this->post->{$name} ?? null;
    }

    public function session(string $name = null) 
    {
        if(!$name) {
            return $this->session;
        }
        return $this->session->{$name} ?? null;
    }

    /**
     * Retorna $_GET e $_POST
     * @return stdClass
     */
    public function all(): stdClass
    {
        return (object) array_merge((array) $this->get, (array) $this->post);
    }

    public function userConect(): bool
    {
        if (!$this->entityUser) {
            return false;
        }
        return $this->permanenceRepository->checkConect($this->entityUser);
    }

    /**
     * @param array $get
     */
    public function setGet(array $get): void
    {
        $this->get = (object) sanitizeMany($get);
    }

    /**
     * @param array $post
     */
    public function setPost(array $post): void
    {
        $this->post = (object) sanitizeMany($post);
    }

    /**
     * @param array $session
     */
    public function setSession(array $session = []): void
    {
        $this->session = (object) sanitizeMany($session);
    }

    /**
     * @param EntityUser $user
     * @param userPermancenceRepository $permanence
     */
    public function setUsuario(EntityUser $user, userPermancenceRepository $permanence): void
    {
        $this->EntityUser = $user;
        $this->permanenceRepository = $permanence;
    }
}