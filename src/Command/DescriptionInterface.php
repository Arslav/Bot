<?php

namespace Arslav\Bot\Command;

interface DescriptionInterface
{
    /**
     * @return string|null
     */
    function getDescription(): ?string;
}
