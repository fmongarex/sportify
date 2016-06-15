<?php

namespace Devlabs\SportifyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Devlabs\SportifyBundle\Entity\MatchRepository")
 * @ORM\Table(name="matches")
 */
class Match
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", length=100)
     */
    private $datetime;

    /**
     * @ORM\Column(type="string", length=50, name="home_team")
     */
    private $homeTeam;

    /**
     * @ORM\Column(type="string", length=50, name="away_team")
     */
    private $awayTeam;

    /**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="matchesHomeTeam")
     * @ORM\JoinColumn(name="home_team_id", referencedColumnName="id")
     */
    private $homeTeamId;

    /**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="matchesAwayTeam")
     * @ORM\JoinColumn(name="away_team_id", referencedColumnName="id")
     */
    private $awayTeamId;

    /**
     * @ORM\Column(type="integer", name="home_goals", nullable=TRUE)
     */
    private $homeGoals;

    /**
     * @ORM\Column(type="integer", name="away_goals", nullable=true)
     */
    private $awayGoals;

    /**
     * @ORM\ManyToOne(targetEntity="Tournament", inversedBy="matches")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
     */
    private $tournamentId;

    /**
     * @ORM\OneToMany(targetEntity="Prediction" , mappedBy="matchId" , cascade={"all"})
     */
    private $predictions;

    /**
     * Property for indicating whether match form should be locked/disabled
     *
     * @var bool
     */
    private $disabledAttribute = false;

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
     * Set datetime
     *
     * @param \DateTime $datetime
     *
     * @return Match
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set homeTeam
     *
     * @param string $homeTeam
     *
     * @return Match
     */
    public function setHomeTeam($homeTeam)
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    /**
     * Get homeTeam
     *
     * @return string
     */
    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    /**
     * Set awayTeam
     *
     * @param string $awayTeam
     *
     * @return Match
     */
    public function setAwayTeam($awayTeam)
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    /**
     * Get awayTeam
     *
     * @return string
     */
    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    /**
     * Set homeGoals
     *
     * @param integer $homeGoals
     *
     * @return Match
     */
    public function setHomeGoals($homeGoals)
    {
        $this->homeGoals = $homeGoals;

        return $this;
    }

    /**
     * Get homeGoals
     *
     * @return integer
     */
    public function getHomeGoals()
    {
        return $this->homeGoals;
    }

    /**
     * Set awayGoals
     *
     * @param integer $awayGoals
     *
     * @return Match
     */
    public function setAwayGoals($awayGoals)
    {
        $this->awayGoals = $awayGoals;

        return $this;
    }

    /**
     * Get awayGoals
     *
     * @return integer
     */
    public function getAwayGoals()
    {
        return $this->awayGoals;
    }

    /**
     * Set tournamentId
     *
     * @param \Devlabs\SportifyBundle\Entity\Tournament $tournamentId
     *
     * @return Match
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

    public function __toString() {
        return "$this->id";
    }

    /**
     * Get the outcome of the match
     *
     * @return string
     */
    public function getResultOutcome()
    {
        if ($this->homeGoals > $this->awayGoals) {
            return '1';
        } else if ($this->homeGoals < $this->awayGoals) {
            return '2';
        }

        return 'X';
    }

    /**
     * Check if match has started by comparing the current time with the match's datetime
     *
     * @return bool
     */
    public function hasStarted()
    {
        return (time() >= strtotime($this->datetime->format('Y-m-d H:i:s')));
    }

    /**
     * Get disabled
     *
     * @return mixed
     */
    public function getDisabledAttribute()
    {
        return $this->disabledAttribute;
    }

    /**
     * Set disabled
     */
    public function setDisabledAttribute()
    {
        $this->disabledAttribute = true;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->predictions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add prediction
     *
     * @param \Devlabs\SportifyBundle\Entity\Prediction $prediction
     *
     * @return Match
     */
    public function addPrediction(\Devlabs\SportifyBundle\Entity\Prediction $prediction)
    {
        $this->predictions[] = $prediction;

        return $this;
    }

    /**
     * Remove prediction
     *
     * @param \Devlabs\SportifyBundle\Entity\Prediction $prediction
     */
    public function removePrediction(\Devlabs\SportifyBundle\Entity\Prediction $prediction)
    {
        $this->predictions->removeElement($prediction);
    }

    /**
     * Get predictions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPredictions()
    {
        return $this->predictions;
    }

    /**
     * Set homeTeamId
     *
     * @param \Devlabs\SportifyBundle\Entity\Team $homeTeamId
     *
     * @return Match
     */
    public function setHomeTeamId(\Devlabs\SportifyBundle\Entity\Team $homeTeamId = null)
    {
        $this->homeTeamId = $homeTeamId;

        return $this;
    }

    /**
     * Get homeTeamId
     *
     * @return \Devlabs\SportifyBundle\Entity\Team
     */
    public function getHomeTeamId()
    {
        return $this->homeTeamId;
    }

    /**
     * Set awayTeamId
     *
     * @param \Devlabs\SportifyBundle\Entity\Team $awayTeamId
     *
     * @return Match
     */
    public function setAwayTeamId(\Devlabs\SportifyBundle\Entity\Team $awayTeamId = null)
    {
        $this->awayTeamId = $awayTeamId;

        return $this;
    }

    /**
     * Get awayTeamId
     *
     * @return \Devlabs\SportifyBundle\Entity\Team
     */
    public function getAwayTeamId()
    {
        return $this->awayTeamId;
    }
}
