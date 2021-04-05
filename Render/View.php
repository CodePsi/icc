<?php
namespace Icc\Render;

class View
{
    private $resourceName;
    public function __construct(string $resourceName)
    {
        $this -> resourceName = $resourceName;
    }



    /**
     * @return string
     */
    public function getResourceName(): string
    {
        return $this->resourceName;
    }
}