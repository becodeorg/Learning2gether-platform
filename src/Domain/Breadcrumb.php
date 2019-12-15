<?php


namespace App\Domain;

class Breadcrumb
{
    private $routeName;
    private $routeOptions;
    private $name;

    public function __construct(string $name, string $routeName='', array $routeOptions=[])
    {
        $this->routeName = $routeName;
        $this->routeOptions = $routeOptions;
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }


    public function getRouteOptions(): ?array
    {
        return $this->routeOptions;
    }



}