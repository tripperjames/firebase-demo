<?php

namespace YOOtheme;

class CsrfProvider
{
    /**
     * Session key for the token.
     *
     * @var string
     */
    protected $name = '_csrf';

    /**
     * Generate token callable.
     *
     * @var callable
     */
    protected $generate;

    /**
     * Constructor.
     *
     * @param callable $generate
     */
    public function __construct(callable $generate = null)
    {
        $this->generate = $generate;
    }

    /**
     * Generates a CSRF token.
     *
     * @return string
     */
    public function generate()
    {
        if (is_callable($this->generate)) {
            return call_user_func($this->generate);
        }

        return sha1($this->getSessionId().$this->getSessionToken());
    }

    /**
     * Validates a CSRF token.
     *
     * @param  string $token
     * @return bool
     */
    public function validate($token)
    {
        return $token === $this->generate();
    }

    /**
     * Returns the session id.
     *
     * @return string
     */
    protected function getSessionId()
    {
        if (!session_id()) {
            session_start();
        }

        return session_id();
    }

    /**
     * Returns the session token.
     *
     * @return string
     */
    protected function getSessionToken()
    {
        if (!isset($_SESSION[$this->name])) {
            $_SESSION[$this->name] = sha1(uniqid(rand(), true));
        }

        return $_SESSION[$this->name];
    }
}
