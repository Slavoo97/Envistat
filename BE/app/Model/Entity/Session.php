<?php
/**
 * This file is part of the EnvistatAPI project.
 * Copyright (c) 2023 Slavomír Švigar <slavo.svigar@gmail.com>
 */

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Model\Services\SessionRepository")
 * @ORM\Table(name="session")
 * @package App\Model\Entity
 */
class Session
{

    /**
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     *
     * @ORM\Column(type="string", nullable = false)
     * @var string
     */
    private $token;

    /**
     *
     * @ORM\Column(type="datetime", nullable = false)
     * @var \DateTime
     */
    private $start;

    /**
     *
     * @ORM\Column(type="datetime", nullable = true)
     * @var \DateTime
     */
    private $end;

    /**
     *
     * @ORM\Column(type="string", nullable = true)
     * @var string
     */
    private $eventKey = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return \DateTime
     */
    public function getStart(): \DateTime
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     */
    public function setStart(\DateTime $start): void
    {
        $this->start = $start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd(): \DateTime
    {
        return $this->end;
    }

    /**
     * @param \DateTime|null $end
     */
    public function setEnd( $end): void
    {
        $this->end = $end;
    }

    /**
     * @return string
     */
    public function getEventKey(): string
    {
        return $this->eventKey;
    }

    /**
     * @param string|null $eventKey
     */
    public function setEventKey(string|null $eventKey): void
    {
        $this->eventKey = $eventKey;
    }
}
