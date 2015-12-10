<?php

namespace Vacilos\QuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="userquiz")
 * @ORM\HasLifecycleCallbacks
 */
class UserQuiz {

    const NOTSTARTED = 0;
    const INPROGRESS = 1;
    const FINISHED = 2;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Quiz")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $quiz;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status = 0;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

    public function showStatus() {
        switch($this->status) {
            case 1:
                return "Έχει ξεκινήσει";
            break;
            case 2:
                return "Ολοκληρωμένο";
            break;
        }
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
     * Set status
     *
     * @param integer $status
     *
     * @return UserQuiz
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return UserQuiz
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return UserQuiz
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set quiz
     *
     * @param \Vacilos\QuizBundle\Entity\Quiz $quiz
     *
     * @return UserQuiz
     */
    public function setQuiz(\Vacilos\QuizBundle\Entity\Quiz $quiz = null)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return \Vacilos\QuizBundle\Entity\Quiz
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Set user
     *
     * @param \Vacilos\QuizBundle\Entity\User $user
     *
     * @return UserQuiz
     */
    public function setUser(\Vacilos\QuizBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Vacilos\QuizBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
