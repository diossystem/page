<?php

namespace Dios\System\Page\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageNotFoundException extends NotFoundHttpException
{
    /**
     * A URL of the requested page.
     *
     * @var string|null
     */
    protected $url;

    /**
     * Sets a URL to the exception message.
     *
     * @param  string|null $message
     * @return self
     */
    public function setUrl(string $url = null): self
    {
        $this->url = $url;

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

    /**
     * Returns the set URL or null.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url ?? null;
    }
}
