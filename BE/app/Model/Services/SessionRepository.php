<?php declare(strict_types = 1);

/**
 * This file is part of the EnvistatAPI project.
 * Copyright (c) 2023 Slavomír Švigar <slavo.svigar@gmail.com>
 */

namespace App\Model\Services;

use App\Model\Entity\Session;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;

class SessionRepository extends EntityRepository {

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
        parent::__construct ($entityManager, new ClassMetadata(Session::class));

        $this->em = $entityManager;
    }

	/**
	 * @param int $id
	 * @return Session|null
     * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 * @throws \Doctrine\ORM\TransactionRequiredException
	 */
    public function getById(int $id) : ?Session {
    	$user = $this->em->find(Session::class, $id);

    	if($user instanceof Session) {
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
    public function findSessionBy(array $criteria = array(), $orderBy = array(), $limit = NULL, $offset = NULL)
    {
        return $this->findBy($criteria, $orderBy, $limit, $offset);
    }
    public function findOneSessionBy(array $criteria = array())
    {
        return $this->findOneBy($criteria);
    }

    /**
     * @param $token
     * @param $start
     * @param null $end
     * @param null $eventKey
     * @return Session
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create($token, $start, $end = null, $eventKey = null) : Session {

        $session = new Session;
        $session->setToken($token);
        $session->setStart($start);
        $session->setEnd($end);
        $session->setEventKey($eventKey);

        $this->em->persist($session);
        $this->em->flush();

        return $session;
    }

    /**
     * @param $id
     * @param DateTime $end
     * @return Session
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateEnd($id, DateTime $end) : Session {
        $entity = $this->find($id);
        $entity->setEnd($end);

        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

}