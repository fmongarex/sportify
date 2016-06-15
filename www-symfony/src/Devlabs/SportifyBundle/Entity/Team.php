<?php

namespace Devlabs\SportifyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Devlabs\SportifyBundle\Entity\TeamRepository")
 * @ORM\Table(name="teams")
 * @UniqueEntity("name")
 * @UniqueEntity("nameShort")
 */
class Team
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique = true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=10, name="name_short", unique = true)
     */
    private $nameShort;

    /**
     * @ORM\ManyToOne(targetEntity="Tournament", inversedBy="teams")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
     */
    private $tournamentId;

    /**
     * @ORM\OneToMany(targetEntity="PredictionChampion" , mappedBy="teamId" , cascade={"all"})
     */
    private $predictionsChampion;

    /**
     * @ORM\OneToMany(targetEntity="Match" , mappedBy="homeTeamId" , cascade={"all"})
     */
    private $matchesHomeTeam;

    /**
     * @ORM\OneToMany(targetEntity="Match" , mappedBy="awayTeamId" , cascade={"all"})
     */
    private $matchesAwayTeam;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->predictionsChampion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->matchesHomeTeam = new \Doctrine\Common\Collections\ArrayCollection();
        $this->matchesAwayTeam = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Team
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set nameShort
     *
     * @param string $nameShort
     *
     * @return Team
     */
    public function setNameShort($nameShort)
    {
        $this->nameShort = $nameShort;

        return $this;
    }

    /**
     * Get nameShort
     *
     * @return string
     */
    public function getNameShort()
    {
        return $this->nameShort;
    }

    /**
     * Set tournamentId
     *
     * @param \Devlabs\SportifyBundle\Entity\Tournament $tournamentId
     *
     * @return Team
     */
    public function setTournamentId(\Devlabs\SportifyBundle\Entity\Tournament $tournamentId = null)
    {
        $this->tournamentId = $tournamentId;

        return $this;
    }

    /**
     * Get tournamentId
     *
     * @return \Devlabs\SportifyBundle\Entity\Tournament
     */
    public function getTournamentId()
    {
        return $this->tournamentId;
    }

    /**
     * Add predictionsChampion
     *
     * @param \Devlabs\SportifyBundle\Entity\PredictionChampion $predictionsChampion
     *
     * @return Team
     */
    public function addPredictionsChampion(\Devlabs\SportifyBundle\Entity\PredictionChampion $predictionsChampion)
    {
        $this->predictionsChampion[] = $predictionsChampion;

        return $this;
    }

    /**
     * Remove predictionsChampion
     *
     * @param \Devlabs\SportifyBundle\Entity\PredictionChampion $predictionsChampion
     */
    public function removePredictionsChampion(\Devlabs\SportifyBundle\Entity\PredictionChampion $predictionsChampion)
    {
        $this->predictionsChampion->removeElement($predictionsChampion);
    }

    /**
     * Get predictionsChampion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPredictionsChampion()
    {
        return $this->predictionsChampion;
    }

    /**
     * Add matchesHomeTeam
     *
     * @param \Devlabs\SportifyBundle\Entity\Match $matchesHomeTeam
     *
     * @return Team
     */
    public function addMatchesHomeTeam(\Devlabs\SportifyBundle\Entity\Match $matchesHomeTeam)
    {
        $this->matchesHomeTeam[] = $matchesHomeTeam;

        return $this;
    }

    /**
     * Remove matchesHomeTeam
     *
     * @param \Devlabs\SportifyBundle\Entity\Match $matchesHomeTeam
     */
    public function removeMatchesHomeTeam(\Devlabs\SportifyBundle\Entity\Match $matchesHomeTeam)
    {
        $this->matchesHomeTeam->removeElement($matchesHomeTeam);
    }

    /**
     * Get matchesHomeTeam
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatchesHomeTeam()
    {
        return $this->matchesHomeTeam;
    }

    /**
     * Add matchesAwayTeam
     *
     * @param \Devlabs\SportifyBundle\Entity\Match $matchesAwayTeam
     *
     * @return Team
     */
    public function addMatchesAwayTeam(\Devlabs\SportifyBundle\Entity\Match $matchesAwayTeam)
    {
        $this->matchesAwayTeam[] = $matchesAwayTeam;

        return $this;
    }

    /**
     * Remove matchesAwayTeam
     *
     * @param \Devlabs\SportifyBundle\Entity\Match $matchesAwayTeam
     */
    public function removeMatchesAwayTeam(\Devlabs\SportifyBundle\Entity\Match $matchesAwayTeam)
    {
        $this->matchesAwayTeam->removeElement($matchesAwayTeam);
    }

    /**
     * Get matchesAwayTeam
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatchesAwayTeam()
    {
        return $this->matchesAwayTeam;
    }
}
