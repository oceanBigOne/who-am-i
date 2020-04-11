<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait UniqueIdTrait
{

    /**
     * @var int $uniqueIndex;
     */
    private static $uniqueIndex = 0;

    /**
     * @var string $uniqueId
     * @ORM\Column(name="unique_id", type="string", length=60, unique=true)
     */
    private $uniqueId;

    public function getUniqueId(): ?string
    {
        return $this->uniqueId;
    }

    public function setUniqueId(?string $uniqueId): self
    {
        $this->uniqueId = $uniqueId;

        return $this;
    }

    /**
     * @param null|int $size
     * @return string
     */
    private function generateUniqueId(int $size = 60): string
    {
        $id = md5(getenv("APP_SECRET") . (microtime(true) * rand(1, 999999999)));
        self::$uniqueIndex++;
        $date = date("ymdHis");
        if (!is_null($size) && is_int($size)) {
            $id = substr($id, 0, $size - (mb_strlen($date) + mb_strlen(self::$uniqueIndex)));
        }
        $id .= $date;
        $id = self::$uniqueIndex . $id;
        return $id;
    }

}