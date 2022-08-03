<?php

namespace Arslav\Newbot\Entities\Traits;

use Carbon\Carbon;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

trait TimestampTrait
{

    /** @Column(type="integer") */
    protected $created_at;

    /** @Column(type="integer") */
    protected $updated_at;

    /**
     * @PrePersist
     * @PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $dateTimeNow = Carbon::now();

        $this->setUpdatedAt($dateTimeNow);

        if ($this->created_at === null) {
            $this->setCreatedAt($dateTimeNow);
        }
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt()
    {
        return Carbon::createFromTimestamp($this->created_at);
    }

    /**
     * @param Carbon $created_at
     */
    public function setCreatedAt(Carbon $created_at): void
    {
        $this->created_at = $created_at->getTimestamp();
    }

    public function getUpdatedAt()
    {
        return Carbon::createFromTimestamp($this->updated_at);
    }

    /**
     * @param Carbon $updated_at
     */
    public function setUpdatedAt(Carbon $updated_at): void
    {
        $this->updated_at = $updated_at->getTimestamp();
    }
}