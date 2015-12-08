<?php

namespace Vacilos\QuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="userquizanswer")
 * @ORM\HasLifecycleCallbacks
 */
class UserQuizAnswer {

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
     * @ORM\ManyToOne(targetEntity="UserQuiz")
     * @ORM\JoinColumn(name="userquiz_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $userquiz;

    /**
     * @ORM\ManyToOne(targetEntity="QuizQuestion")
     * @ORM\JoinColumn(name="quizquestion_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $quizquestion;


    /**
     * @ORM\ManyToOne(targetEntity="Answer")
     * @ORM\JoinColumn(name="answer_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $answer;

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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return UserQuizAnswer
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
     * @return UserQuizAnswer
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
     * Set userquiz
     *
     * @param \Vacilos\QuizBundle\Entity\UserQuiz $userquiz
     *
     * @return UserQuizAnswer
     */
    public function setUserquiz(\Vacilos\QuizBundle\Entity\UserQuiz $userquiz = null)
    {
        $this->userquiz = $userquiz;

        return $this;
    }

    /**
     * Get userquiz
     *
     * @return \Vacilos\QuizBundle\Entity\UserQuiz
     */
    public function getUserquiz()
    {
        return $this->userquiz;
    }

    /**
     * Set quizquestion
     *
     * @param \Vacilos\QuizBundle\Entity\QuizQuestion $quizquestion
     *
     * @return UserQuizAnswer
     */
    public function setQuizquestion(\Vacilos\QuizBundle\Entity\QuizQuestion $quizquestion = null)
    {
        $this->quizquestion = $quizquestion;

        return $this;
    }

    /**
     * Get quizquestion
     *
     * @return \Vacilos\QuizBundle\Entity\QuizQuestion
     */
    public function getQuizquestion()
    {
        return $this->quizquestion;
    }

    /**
     * Set answer
     *
     * @param \Vacilos\QuizBundle\Entity\Answer $answer
     *
     * @return UserQuizAnswer
     */
    public function setAnswer(\Vacilos\QuizBundle\Entity\Answer $answer = null)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return \Vacilos\QuizBundle\Entity\Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }
}
