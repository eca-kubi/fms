<?php


class Body extends GenericEntity
{
    public string $contentType;
    public string $content;

    public function __construct(string $contentType, string $content)
    {
        parent::__construct();
        $this->contentType = $contentType;
        $this->content = $content;
    }

}
