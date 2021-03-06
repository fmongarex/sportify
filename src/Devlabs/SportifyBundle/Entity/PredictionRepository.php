<?php

namespace Devlabs\SportifyBundle\Entity;

/**
 * Class PredictionRepository
 * @package Devlabs\SportifyBundle\Entity
 */
class PredictionRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Get a list of the predictions for matches which have not been scored/finished yet
     *
     * @param User $user
     * @return array
     */
    public function getNotScored(User $user, $tournamentId, $dateFrom, $dateTo)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('p')
            ->from('DevlabsSportifyBundle:Prediction', 'p')
            ->join('p.matchId', 'm')
            ->join('m.homeTeamId', 'tm')
            ->join('m.tournamentId', 't')
            ->join('t.scores', 's', 'WITH', 's.userId = :user_id')
            ->where('p.userId = :user_id')
            ->andWhere('p.scoreAdded IS NULL OR p.scoreAdded = 0')
            ->andWhere('m.homeGoals IS NULL OR m.awayGoals IS NULL')
            ->andWhere('m.datetime >= :date_from AND m.datetime <= :date_to')
            ->orderBy('m.datetime')
            ->addOrderBy('m.tournamentId')
            ->addOrderBy('tm.name')
            ->setParameters(array(
                'user_id' => $user->getId(),
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ));

        // prepare a different query, if a tournament is selected for filtering
        if ($tournamentId !== 'all') {
            $query->andWhere('m.tournamentId = :tournament_id')
                ->setParameter('tournament_id', $tournamentId);
        }

        $queryResult = $query->getQuery()->getResult();

        $result = array();

        /**
         * Iterate the query result array
         * and set the item key to be the match id
         */
        foreach ($queryResult as $prediction) {
            $result[$prediction->getMatchId()->getId()] = $prediction;
        }

        return $result;
    }

    /**
     * Method for getting a list of the predictions for matches which have already been scored/finished
     *
     * @param User $user
     * @param $tournamentId
     * @param $dateFrom
     * @param $dateTo
     * @return array
     */
    public function getAlreadyScored(User $user, $tournamentId, $dateFrom, $dateTo)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('p')
            ->from('DevlabsSportifyBundle:Prediction', 'p')
            ->join('p.matchId', 'm')
            ->join('m.homeTeamId', 'tm')
            ->join('m.tournamentId', 't')
            ->join('t.scores', 's', 'WITH', 's.userId = :user_id')
            ->where('p.userId = :user_id')
            ->andWhere('p.scoreAdded = 1')
            ->andWhere('m.homeGoals IS NOT NULL AND m.awayGoals IS NOT NULL')
            ->andWhere('m.datetime >= :date_from AND m.datetime <= :date_to')
            ->orderBy('m.datetime', 'DESC')
            ->addOrderBy('m.tournamentId')
            ->addOrderBy('tm.name')
            ->setParameters(array(
                'user_id' => $user->getId(),
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ));

        // prepare a different query, if a tournament is selected for filtering
        if ($tournamentId !== 'all') {
            $query->andWhere('m.tournamentId = :tournament_id')
                ->setParameter('tournament_id', $tournamentId);
        }

        $queryResult = $query->getQuery()->getResult();

        $result = array();

        /**
         * Iterate the query result array
         * and set the item key to be the match id
         */
        foreach ($queryResult as $prediction) {
            $result[$prediction->getMatchId()->getId()] = $prediction;
        }

        return $result;
    }

    /**
     * Get a list of the NOT SCORED predictions for matches
     * which have final score
     *
     * @return array
     */
    public function getFinishedNotScored(User $user)
    {
        $queryResult = $this->getEntityManager()->createQueryBuilder()
            ->select('p')
            ->from('DevlabsSportifyBundle:Prediction', 'p')
            ->join('p.matchId', 'm')
            ->where('p.scoreAdded IS NULL OR p.scoreAdded = 0')
            ->andWhere('m.homeGoals IS NOT NULL AND m.awayGoals IS NOT NULL')
            ->andWhere('p.userId = :user_id')
            ->orderBy('m.id')
            ->setParameters(array('user_id' => $user->getId()))
            ->getQuery()
            ->getResult();

        $result = array();

        /**
         * Iterate the query result array
         * and set the item key to be the match id
         */
        foreach ($queryResult as $prediction) {
            $result[$prediction->getMatchId()->getId()] = $prediction;
        }

        return $result;
    }

    /**
     * Get a single prediction by user and match id
     *
     * @param User $user
     * @param Match $match
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getOneByUserAndMatch(User $user, Match $match)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('p')
            ->from('DevlabsSportifyBundle:Prediction', 'p')
            ->where('p.userId = :user_id')
            ->andWhere('p.matchId = :match_id')
            ->setParameters(array('user_id' => $user->getId(), 'match_id' => $match->getId()))
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * Get a list of user's exact score predictions for a tournament
     *
     * @param User $user
     * @param Tournament $tournament
     * @return array
     */
    public function getExactPredictionsByUserAndTournament(User $user, Tournament $tournament)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('p')
            ->from('DevlabsSportifyBundle:Prediction', 'p')
            ->join('p.matchId', 'm')
            ->where('p.userId = :user_id')
            ->andWhere('p.scoreAdded = 1 AND p.points = :points_exact')
            ->andWhere('m.tournamentId = :tournament_id')
            ->setParameters(array(
                'user_id' => $user->getId(),
                'tournament_id' => $tournament->getId(),
                'points_exact' => Prediction::POINTS_EXACT
            ))
            ->getQuery()
            ->getResult();
    }
}
