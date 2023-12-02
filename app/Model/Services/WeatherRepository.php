<?php declare(strict_types = 1);

/**
 * This file is part of the EnvistatAPI project.
 * Copyright (c) 2023 Slavomír Švigar <slavo.svigar@gmail.com>
 */

namespace App\Model\Services;

use App\Model\Entity\Weather;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\OptimisticLockException;

class WeatherRepository extends EntityRepository {

    /**
     * @var EntityManager
     */
    private EntityManager $em;

    /**
     * PropertyRepository constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct ($entityManager, new ClassMetadata(Weather::class));

        $this->em = $entityManager;
    }

	/**
	 * @param int $id
	 * @return Weather|null
     * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 * @throws \Doctrine\ORM\TransactionRequiredException
	 */
    public function getById(int $id) : ?Weather {
    	$user = $this->em->find(Weather::class, $id);

    	if($user instanceof Weather) {
    		return $user;
		}
		return null;
	}

    /**
     * @param array $criteria
     * @param $orderBy
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function findWeatherBy(array $criteria = array(), $orderBy = array(), $limit = NULL, $offset = NULL)
    {
        return $this->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneWeatherBy(array $criteria = array(), array $orderBy = array())
    {
        return $this->findOneBy($criteria, $orderBy);
    }

	/**
	 * @param $temperature
	 * @param $humidity
	 * @return Weather
	 * @throws ORMException
	 * @throws OptimisticLockException
	 */
    public function create($temperature, $humidity) : Weather {

        $weather = new Weather;
        $weather->setTemperature($temperature);
        $weather->setDate(new DateTime());
		$weather->setHumidity($humidity);

        $this->em->persist($weather);
        $this->em->flush();

        return $weather;
    }

}
