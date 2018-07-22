<?php

/**
 * Class containing information about normalization.
 */
class Norm {

    /**
     * @var null|int Normalization ID.
     */
    private $id;
    /**
     * @var null|int Normalization owner ID.
     */
    private $userId;
    /**
     * @var string Normalized text.
     */
    private $norm;
    /**
     * @var int Number of text normalizations.
     */
    private $text;
    /**
     * @var int Number of phone normalizations.
     */
    private $phone;
    /**
     * @var int Number of date normalizations.
     */
    private $date;

    /**
     * Norm constructor.
     * @param null|int $id Normalization ID.
     * @param null|int $userId Normalization owner ID.
     * @param string $norm Normalized text.
     * @param int $text Number of text normalizations.
     * @param int $phone Number of phone normalizations.
     * @param int $date Number of date normalizations.
     */
    public function __construct(int $id = null, int $userId = null, string $norm,
                                int $text, int $phone, int $date) {
        $this->id = $id;
        $this->userId = $userId;
        $this->norm = $norm;
        $this->text = $text;
        $this->phone = $phone;
        $this->date = $date;
    }

    /**
     * @return int Normalization ID.
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return int Normalization owner ID.
     */
    public function getUserId(): int {
        return $this->userId;
    }

    /**
     * @return string Normalized text.
     */
    public function getNorm(): string {
        return $this->norm;
    }

    /**
     * @return int Number of text normalizations.
     */
    public function getText(): int {
        return $this->text;
    }

    /**
     * @return int Number of phone normalizations.
     */
    public function getPhone(): int {
        return $this->phone;
    }

    /**
     * @return int Number of date normalizations.
     */
    public function getDate(): int {
        return $this->date;
    }

}