<?php
/**
  * User: vacilos
 * Date: 12/2/15
 * Time: 2:18 PM
 */

namespace Vacilos\QuizBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="users")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $team;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Set team
     *
     * @param \Vacilos\QuizBundle\Entity\Team $team
     *
     * @return User
     */
    public function setTeam(\Vacilos\QuizBundle\Entity\Team $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     *
     * @return \Vacilos\QuizBundle\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }
}
