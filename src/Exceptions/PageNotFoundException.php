<?php

namespace Dios\System\Page\Exceptions;

use RuntimeException;

class PageNotFoundException extends RuntimeException
{
    /**
     * The state of the base message.
     *
     * @var bool
     */
    protected $hiddenBaseMessage = false;

    /**
     * Sets a URL to the exception message.
     *
     * @param  string|null $message
     * @return self
     */
    public function setUrl(string $url = null): self
    {
        $message = $url
            ? 'Page not found: '.$url
            : 'Page not found'
        ;

        $this->message = $this->message
            ? $this->message .' '. $message
            : $message
        ;

        return $this;
    }
}
