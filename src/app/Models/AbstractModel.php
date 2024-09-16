<?php

namespace App\Models;

abstract class AbstractModel
{
    public function __construct(
        protected int $id,
        protected string $name,
        protected ?int $parentId = null
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    abstract public function getType(): string;
}
