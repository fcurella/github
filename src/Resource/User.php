<?php declare(strict_types=1);

namespace ApiClients\Github\Resource;

use DateTimeInterface;
use ApiClients\Foundation\Resource\TransportAwareTrait;

abstract class User implements UserInterface
{
    use TransportAwareTrait;
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $login;
    /**
     * @var string
     */
    protected $avatar_url;
    /**
     * @var string
     */
    protected $type;
    /**
     * @var bool
     */
    protected $site_admin;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $company;
    /**
     * @var string
     */
    protected $blog;
    /**
     * @var string
     */
    protected $location;
    /**
     * @var string
     */
    protected $email;
    /**
     * @var bool
     */
    protected $hireable;
    /**
     * @var string
     */
    protected $bio;
    /**
     * @var string
     */
    protected $public_repos;
    /**
     * @var string
     */
    protected $public_gists;
    /**
     * @var string
     */
    protected $followers;
    /**
     * @var string
     */
    protected $following;
    /**
     * @var DateTimeInterface
     */
    protected $created_at;
    /**
     * @var DateTimeInterface
     */
    protected $updated_at;
    /**
     * @return int
     */
    public function id() : int
    {
        return $this->id;
    }
    /**
     * @return string
     */
    public function login() : string
    {
        return $this->login;
    }
    /**
     * @return string
     */
    public function avatar() : string
    {
        return $this->avatar_url;
    }
    /**
     * @return string
     */
    public function type() : string
    {
        return $this->type;
    }
    /**
     * @return bool
     */
    public function isSiteAdmin() : bool
    {
        return $this->site_admin;
    }
    /**
     * @return string
     */
    public function name() : string
    {
        return $this->name;
    }
    /**
     * @return string
     */
    public function company() : string
    {
        return $this->company;
    }
    /**
     * @return string
     */
    public function blog() : string
    {
        return $this->blog;
    }
    /**
     * @return string
     */
    public function location() : string
    {
        return $this->location;
    }
    /**
     * @return string
     */
    public function email() : string
    {
        return $this->email;
    }
    /**
     * @return bool
     */
    public function isHireable() : bool
    {
        return $this->hireable;
    }
    /**
     * @return string
     */
    public function bio() : string
    {
        return $this->bio;
    }
    /**
     * @return string
     */
    public function publicRepos() : string
    {
        return $this->public_repos;
    }
    /**
     * @return string
     */
    public function publicGists() : string
    {
        return $this->public_gists;
    }
    /**
     * @return string
     */
    public function followers() : string
    {
        return $this->followers;
    }
    /**
     * @return string
     */
    public function following() : string
    {
        return $this->following;
    }
    /**
     * @return DateTimeInterface
     */
    public function createdAt() : DateTimeInterface
    {
        return $this->created_at;
    }
    /**
     * @return DateTimeInterface
     */
    public function updatedAt() : DateTimeInterface
    {
        return $this->updated_at;
    }
    
    public function refresh()
    {
        // TODO
    }
}