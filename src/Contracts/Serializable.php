<?php

namespace Looqey\Speca\Contracts;

interface Serializable
{
    public function toArray(): array;
    public function toJson(int $options = 0): string;
}
